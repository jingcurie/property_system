  <!-- 附件 -->
  <div class="col-md-12">
      @include('components.files.upload', [
          'files' => $lease->files ?? [],
          'fileable_type' => $lease ? get_class($lease) : App\Models\RentalApplication::class,
          'fileable_id' => $lease->lease_id,
          'attachments' => $attachments,
      ])
  </div>
