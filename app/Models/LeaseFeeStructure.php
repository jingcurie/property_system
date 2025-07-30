<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $lease_id 租赁ID
 * @property string $unit_type 户型：1 Bdrm, 2 Bdrm 1 Bath等
 * @property string|null $mandatory_cleaning_fee 强制清洁费
 * @property int $cleaning_fee_paid 清洁费是否已付
 * @property string $move_out_inspection_fee 搬出检查费
 * @property string|null $move_in_fee 搬入费
 * @property string|null $move_out_fee 搬出费
 * @property int $elevator_booking_required 是否需要预约电梯
 * @property int $elevator_booking_notice_days 电梯预约提前天数
 * @property string $key_deposit 钥匙押金
 * @property string $fob_deposit 门禁卡押金
 * @property string $key_loan_fee_regular 常规时间借钥匙费
 * @property string $key_loan_fee_after_hours 非工作时间借钥匙费
 * @property string|null $lease_break_fee_half_month 违约费-半月（租户找替换者）
 * @property string|null $lease_break_fee_one_month 违约费-一月（房东找替换者）
 * @property string|null $lease_break_fee_two_month 违约费-两月（立即解约）
 * @property string $created_at
 * @property-read \App\Models\Lease $lease
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereCleaningFeePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereElevatorBookingNoticeDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereElevatorBookingRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereFobDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereKeyDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereKeyLoanFeeAfterHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereKeyLoanFeeRegular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseBreakFeeHalfMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseBreakFeeOneMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseBreakFeeTwoMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMandatoryCleaningFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMoveInFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMoveOutFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMoveOutInspectionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereUnitType($value)
 * @mixin \Eloquent
 */
class LeaseFeeStructure extends Model
{
    protected $table = 'lease_fee_structure';

    protected $fillable = [
        'lease_id',
        'unit_type',
        'mandatory_cleaning_fee',
        'cleaning_fee_paid',
        'move_out_inspection_fee',
        'move_in_fee',
        'move_out_fee',
        'elevator_booking_required',
        'elevator_booking_notice_days',
        'key_deposit',
        'fob_deposit',
        'key_loan_fee_regular',
        'key_loan_fee_after_hours',
        'lease_break_fee_half_month',
        'lease_break_fee_one_month',
        'lease_break_fee_two_month',
    ];

    public $timestamps = false; // 因为没有 `updated_at`

    // 关联主租赁记录
    public function lease()
    {
        return $this->belongsTo(Lease::class, 'lease_id', 'lease_id');
    }
}
