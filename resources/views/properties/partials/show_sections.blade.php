@php
$feature = $property->feature;
$amenity = $property->amenity;
$rental = $property->rentalInfo;
$financial = $property->financialInfo;
$compliance = $property->complianceInfo;
$utilities = collect(explode(',', $rental->utilities_included ?? ''));
  
use Illuminate\Support\Str;
@endphp

<!-- 顶部基本信息 + 轮播图 -->
<div class="row mb-4">
  <div class="col-md-6">
    @if($property->media->count())
      <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          @php
            $coverShown = false;
          @endphp
          @foreach($property->media as $index => $media)
            @if(Str::endsWith($media->file_name, ['.mp4', '.mov', '.webm']))
              <div class="carousel-item {{ !$coverShown ? 'active' : '' }}">
                <video controls
                        class="d-block"
                        style="max-height: 400px; object-fit: contain; margin: 0 auto;">
                  <source src="{{ url('/media/property/' . Str::after($media->file_path, 'property_media/')) }}">
                </video>
              </div>
              @php $coverShown = true; @endphp
            @endif
          @endforeach
          @foreach($property->media as $index => $media)
            @if(!Str::endsWith($media->file_name, ['.mp4', '.mov', '.webm']))
              <div class="carousel-item {{ !$coverShown ? 'active' : '' }}">
                <img src="{{ url('/media/property/' . Str::after($media->file_path, 'property_media/')) }}"
                  class="d-block"
                  style="max-height: 400px; object-fit: contain; margin: 0 auto;">
              </div>
              @php $coverShown = true; @endphp
            @endif
          @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    @else
      <div class="bg-light d-flex justify-content-center align-items-center" style="height:300px;">
        <span class="text-muted">无封面图</span>
      </div>
    @endif
  </div>
  <div class="col-md-6">
    <h4 class="fw-bold">{{ $property->property_name }}</h4>
    <p class="text-muted">{{ $property->address_street }}, {{ $property->address_city }}, {{ $property->address_province }} {{ $property->address_postal_code }}</p>
    <p>类型：{{ $property->property_type }}</p>
    <p>业权类型：{{ $property->ownership_type }}</p>
    <p>建造年份：{{ $property->year_built ?: '—' }}</p>
    <p>租金：${{ number_format($rental->monthly_rent, 2) }} / 月</p>
    <p>押金：${{ number_format($rental->security_deposit, 2) }}</p>
    <p>状态：<span class="badge bg-success">{{ $rental->availability_status }}</span></p>
    <p>房东：{{ optional(optional($property->ownership)->owner)->full_name ?? '未指定' }}</p>
  </div>
</div>

<!-- 房屋特征 -->
<!-- 房屋特征 -->
<div class="card mb-4">
  <div class="card-header fw-bold">房屋特征</div>
  <div class="card-body row g-3">
    <div class="col-md-3">卧室数：{{ $feature->bedrooms }}</div>
    <div class="col-md-3">卫浴数：{{ $feature->bathrooms }}</div>
    <div class="col-md-3">面积：{{ $feature->square_footage }} 平方英尺</div>
    <div class="col-md-3">停车位：{{ $feature->parking_spaces }}</div>
    <div class="col-md-3">停车类型：{{ $feature->parking_type }}</div>
    <div class="col-md-3">供暖类型：{{ $feature->heating_type }}</div>
    <div class="col-md-3">制冷类型：{{ $feature->cooling_type }}</div>
    <div class="col-md-3">带家具：<span class="badge bg-{{ $feature->furnished ? 'success' : 'secondary' }}">{{ $feature->furnished ? '是' : '否' }}</span></div>
    <div class="col-md-3">洗衣方式：{{ $feature->laundry }}</div>
  </div>
</div>

<!-- 配套设施 -->
<div class="card mb-4">
  <div class="card-header fw-bold">配套设施</div>
  <div class="card-body row g-3">
    @foreach([
      'has_gym' => '健身房',
      'has_pool' => '游泳池',
      'has_balcony' => '阳台',
      'has_elevator' => '电梯',
      'has_dishwasher' => '洗碗机',
      'has_fridge' => '冰箱',
      'has_stove' => '炉灶',
      'has_microwave' => '微波炉',
      'has_air_conditioning' => '空调',
    ] as $key => $label)
      <div class="col-md-3">
        <span class="badge bg-{{ $amenity->$key ? 'success' : 'secondary' }}">{{ $label }}</span>
      </div>
    @endforeach
  </div>
</div>

<!-- 出租信息 -->
<div class="card mb-4">
  <div class="card-header fw-bold">出租信息</div>
  <div class="card-body row g-3">
    <div class="col-md-3">租金：${{ number_format($rental->monthly_rent, 2) }}</div>
    <div class="col-md-3">押金：${{ number_format($rental->security_deposit, 2) }}</div>
    <div class="col-md-3">状态：<span class="badge bg-primary">{{ $rental->availability_status }}</span></div>
    <div class="col-md-3">租期：{{ $rental->lease_term_type }}</div>
    <div class="col-md-3">最短租期：{{ $rental->min_lease_term }} 月</div>
    <div class="col-md-3">可入住：{{ $rental->available_date }}</div>
    <div class="col-md-3">宠物政策：{{ $rental->pet_policy }}</div>
    <div class="col-md-3">宠物附加费：${{ number_format($rental->pet_fee, 2) }}</div>
    <div class="col-md-12">包含水电：
      @foreach(['Water','Electricity','Gas','Internet','Cable'] as $item)
        @if($utilities->contains($item))
          <span class="badge bg-info text-dark me-1">{{ $item }}</span>
        @endif
      @endforeach
    </div>
  </div>
</div>

<!-- 财务信息 -->
<div class="card mb-4">
  <div class="card-header fw-bold">财务信息</div>
  <div class="card-body row g-3">
    <div class="col-md-3">管理费比例：{{ $financial->management_fee_percentage }}%</div>
    <div class="col-md-3">年物业税：${{ number_format($financial->annual_property_tax, 2) }}</div>
    <div class="col-md-3">维修基金：${{ number_format($financial->maintenance_fund, 2) }}</div>
    <div class="col-md-3">已含HST：<span class="badge bg-{{ $financial->hst_included ? 'success' : 'secondary' }}">{{ $financial->hst_included ? '是' : '否' }}</span></div>
  </div>
</div>

<!-- 合规信息 -->
<div class="card mb-4">
  <div class="card-header fw-bold">合规信息</div>
  <div class="card-body row g-3">
    <div class="col-md-4">物业税号：{{ $compliance->property_tax_id }}</div>
    <div class="col-md-4">租赁许可证编号：{{ $compliance->rental_license_number }}</div>
    <div class="col-md-4">保险单号：{{ $compliance->insurance_policy_number }}</div>
    <div class="col-md-4">消防合规：<span class="badge bg-{{ $compliance->fire_safety_compliance ? 'success' : 'secondary' }}">{{ $compliance->fire_safety_compliance ? '是' : '否' }}</span></div>
    <div class="col-md-4">无障碍合规：<span class="badge bg-{{ $compliance->accessibility_compliance ? 'success' : 'secondary' }}">{{ $compliance->accessibility_compliance ? '是' : '否' }}</span></div>
    <div class="col-md-4">最近检查日期：{{ $compliance->last_inspection_date }}</div>
  </div>
</div>
