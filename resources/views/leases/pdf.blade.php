<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lease Agreement</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            padding: 30px;
            border: 1px solid #ccc;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .signature {
            margin-top: 60px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td,
        th {
            padding: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Residential Tenancy Agreement</h2>

    <p><strong>Landlord:</strong> {{ $lease->landlord->full_name ?? 'Sutton-Group MetroLand Realty' }}</p>
    <p><strong>Tenant:</strong> {{ $lease->tenant->first_name . ' ' .  $lease->tenant->last_name}}</p>
    <p><strong>Rental Address:</strong> {{ $lease->property->address_street . ' ' .  $lease->property->address_city}}</p>
    <p><strong>Lease Term:</strong> {{ $lease->start_date }} - {{ $lease->end_date }}</p>
    <p><strong>Rent:</strong> ${{ $lease->rent_amount }} per month</p>

    <div class="section-title">1. Security Deposit</div>
    <p>Security deposit of ${{ $lease->security_deposit }} received on {{ $lease->deposit_date }}.</p>

    <div class="section-title">2. Cleaning Fee</div>
    <p>A prepaid cleaning fee of ${{ $lease->cleaning_fee }} is required before move-in.</p>

    <div class="section-title">3. Additional Terms</div>
    <p>{{ $lease->additional_terms }}</p>

    {{-- Add more clauses based on your lease fields --}}
    {{-- ========= Section 1: Application of the Residential Tenancy Act ========= --}}
    <div class="section-title">1. Application of the Residential Tenancy Act</div>
    <p>
        The terms of this tenancy agreement must comply with the Residential Tenancy Act (RTA) of British Columbia.
        If any clause contradicts the Act, it is considered void.
        Any changes to this agreement must be made in writing and initialed by both landlord and tenant.
    </p>

    {{-- ========= Section 2: Beginning and Term of the Agreement ========= --}}
    <div class="section-title">2. Beginning and Term of the Agreement</div>
    <p>
        This tenancy begins on <strong>{{ $lease->start_date->format('F d, Y') }}</strong> and
        @if ($lease->is_fixed_term)
            ends on <strong>{{ $lease->end_date->format('F d, Y') }}</strong> as a fixed-term tenancy.
        @else
            continues on a month-to-month basis.
        @endif
    </p>

    {{-- ========= Section 3: Rent Details ========= --}}
    <div class="section-title">3. Rent</div>
    <p>
        The tenant agrees to pay <strong>${{ number_format($lease->rent_amount, 2) }}</strong> on the
        <strong>{{ \Carbon\Carbon::parse($lease->rent_due_date)->format('jS') }}</strong> of each month.
        The rent includes the following services:
    </p>
    <ul>
        @if ($lease->includes_utilities)
            <li>Utilities</li>
        @endif
        @if ($lease->includes_internet)
            <li>Internet</li>
        @endif
        @if ($lease->includes_parking)
            <li>Parking</li>
        @endif
        @if ($lease->includes_furniture)
            <li>Furniture</li>
        @endif
        @if ($lease->includes_laundry)
            <li>Laundry</li>
        @endif
    </ul>

    {{-- ========= Section 4: Security Deposit ========= --}}
    <div class="section-title">4. Security Deposit</div>
    <p>
        The tenant has paid a security deposit of <strong>${{ number_format($lease->security_deposit, 2) }}</strong>
        on <strong>{{ optional($lease->deposit_date)->format('F d, Y') }}</strong>.
    </p>

    {{-- ========= Section 5: Pets ========= --}}
    <div class="section-title">5. Pets</div>
    <p>
        @if ($lease->allows_pets)
            Pets are allowed, subject to the terms outlined in the Pet Addendum.
        @else
            No pets are allowed in the rental unit or on the property. This includes visitors' pets.
            Violation of this clause may result in a fine of $500 and potential termination of the tenancy.
        @endif
    </p>

    {{-- ========= Section 6: Condition Inspection ========= --}}
    <div class="section-title">6. Condition Inspections</div>
    <p>
        The landlord and tenant must inspect the condition of the rental unit:
    </p>
    <ul>
        <li>Before move-in (on <strong>{{ $lease->start_date->format('F d, Y') }}</strong>),</li>
        <li>If a pet is added during tenancy, and</li>
        <li>Upon move-out.</li>
    </ul>
    <p>
        Both parties must sign the condition inspection report at each stage. Failure to comply may void rights to
        claims on the security deposit.
    </p>

    {{-- ========= Section 7: Payment of Rent ========= --}}
    <div class="section-title">7. Payment of Rent</div>
    <p>
        The tenant must pay the rent on time. Late payment may result in a 10-day Notice to End Tenancy.
        The landlord agrees not to charge extra for included services (unless permitted under the RTA).
        A receipt will be issued for any rent paid in cash.
    </p>

    {{-- ========= Section 8: Rent Increase ========= --}}
    <div class="section-title">8. Rent Increase</div>
    <p>
        Rent may be increased once every 12 months, in accordance with the Residential Tenancy Act regulations.
        The landlord will provide at least 3 full months’ written notice prior to the rent increase.
        The current rent is <strong>${{ number_format($lease->rent_amount, 2) }}</strong>.
    </p>

    {{-- ========= Section 9: Assignment or Sublet ========= --}}
    <div class="section-title">9. Assignment or Sublet</div>
    <p>
        The tenant must not assign or sublet the unit without prior written consent from the landlord.
        If the tenancy term has 6 or more months remaining, consent may not be unreasonably withheld.
        Any unauthorized assignment or sublet is a material breach of this agreement.
    </p>

    {{-- ========= Section 10: Repairs and Maintenance ========= --}}
    <div class="section-title">10. Repairs</div>
    <p><strong>Landlord Responsibilities:</strong></p>
    <ul>
        <li>Maintain the premises in a safe and habitable condition, per health and safety regulations.</li>
    </ul>
    <p><strong>Tenant Responsibilities:</strong></p>
    <ul>
        <li>Keep the unit clean and sanitary.</li>
        <li>Repair damages caused by themselves or their guests (excluding reasonable wear and tear).</li>
    </ul>
    <p><strong>Emergency Repairs:</strong></p>
    <ul>
        <li>The landlord will provide emergency contact info.</li>
        <li>The tenant must attempt to contact the landlord before undertaking emergency repairs.</li>
        <li>Emergency repairs may be reimbursed if procedures are followed and receipts are submitted.</li>
    </ul>

    {{-- ========= Section 11: Occupants and Guests ========= --}}
    <div class="section-title">11. Occupants and Guests</div>
    <p>
        Only the individuals listed on this agreement may reside in the unit. Guests are allowed under reasonable
        circumstances,
        but the number and frequency must not disturb other residents. Unapproved long-term occupancy (more than 14
        days/year for
        guests, or 30 days/year for immediate family) may be considered a breach.
    </p>

    {{-- ========= Section 12: Locks ========= --}}
    <div class="section-title">12. Locks and Access =========</div>
    <p>
        The landlord may not change locks without providing the tenant with new keys.
        The tenant may not change locks without written permission from the landlord.
        Any unauthorized change may result in fines or breach of contract.
    </p>

    {{-- ========= Section 13: Landlord's Entry ========= --}}
    <div class="section-title">13. Landlord’s Entry to the Rental Unit</div>
    <p>
        The landlord may enter the unit only under the following conditions:
    </p>
    <ul>
        <li>At least 24 hours written notice is provided with a valid reason;</li>
        <li>In case of emergency to protect life or property;</li>
        <li>With tenant’s consent at time of entry;</li>
        <li>The tenant has abandoned the unit;</li>
        <li>An arbitrator or court has issued an order permitting entry.</li>
    </ul>
    <p>
        The landlord may conduct monthly inspections with written notice.
    </p>

    {{-- ========= Section 14: Ending the Tenancy ========= --}}
    <div class="section-title">14. Ending the Tenancy</div>
    <p>
        The tenant may end a periodic tenancy with one full month’s written notice.
        A fixed-term tenancy converts to month-to-month unless otherwise agreed.
        The landlord may only end tenancy for reasons outlined in the Residential Tenancy Act.
    </p>

    {{-- ========= Section 15: Delivery of Agreement ========= --}}
    <div class="section-title">15. Delivery of the Agreement</div>
    <p>
        The landlord must provide the tenant with a copy of this signed agreement within 21 days of the tenancy start
        date.
    </p>

    {{-- ========= Addendum Section Start ========= --}}
    <div class="section-title">Addendum to Residential Tenancy Agreement</div>

    {{-- ========= A1. Occupancy ========= --}}
    <p><strong>1. Occupancy:</strong> Only the following individual(s) may occupy the premises:</p>
    <ul>
        @foreach ($lease->occupants ?? [] as $occupant)
            <li>{{ $occupant->full_name }}</li>
        @endforeach
    </ul>
    <p>
        Guests are limited to 14 cumulative days per calendar year. Immediate family members may stay up to 30
        cumulative days.
        Violations may incur a fine of half-month rent and constitute breach of lease.
    </p>

    {{-- ========= A2. Cleaning Fee ========= --}}
    <p><strong>2. Cleaning Fee:</strong> A mandatory cleaning fee of
        <strong>${{ number_format($lease->cleaning_fee, 2) }}</strong> is to be prepaid before move-in.
    </p>
    <p>
        Failure to complete move-out inspection on scheduled time will result in an extra $200 fee.
        Premature lease termination may incur additional cleaning fees based on unit type.
    </p>

    {{-- ========= A3. Breaking the Lease ========= --}}
    <p><strong>3. Early Termination:</strong> The tenant must give one full month’s written notice. Penalties include:
    </p>
    <ul>
        <li>Rent until replacement tenant is found</li>
        <li>Admin fee for re-renting</li>
        <li>Additional cleaning fees</li>
    </ul>

    {{-- ========= A4. Re-rental Admin Fee ========= --}}
    <p><strong>4. Re-Rental Fees:</strong></p>
    <ul>
        <li>Tenant finds replacement: <strong>½ month rent</strong></li>
        <li>Landlord finds replacement: <strong>1 month rent</strong> (finder’s fee, non-refundable)</li>
        <li>Mutual release: <strong>2 months rent + forfeit of deposit</strong></li>
    </ul>

    {{-- ========= A5. Overholding ========= --}}
    <p><strong>5. Overholding:</strong> Tenant must vacate by 1:00 PM on lease end date. Late stay incurs
        <strong>$300/day</strong> penalty.
    </p>

    {{-- ========= A6. Repairs ========= --}}
    <p><strong>6. Repairs:</strong> Minor clogs or misuse-related damage are the tenant's responsibility. Major repairs
        must be reported promptly.</p>

    {{-- ========= A7. Keys and FOBs ========= --}}
    <p><strong>7. Keys & FOBs:</strong> All keys, fobs, remotes must be returned at the end of tenancy.
        Lost/damaged items will be charged from deposit. Daily fee for late returns: <strong>$35/day</strong> after 7
        days.</p>

    {{-- ========= A8. Insurance ========= --}}
    <p><strong>8. Insurance:</strong> Tenant must carry renter’s insurance including liability coverage.
        Landlord is not liable for damage or loss of tenant belongings.</p>

    {{-- ========= A9. Pets ========= --}}
    <p><strong>9. Pets:</strong> No pets allowed. Violation incurs <strong>$500 fine per occurrence</strong> plus
        potential eviction.</p>

    {{-- ========= A10. Smoking ========= --}}
    <p><strong>10. Smoking:</strong> Absolutely no smoking of any kind inside unit or on balconies. Violation incurs
        <strong>$500 fine</strong> plus repair fees.
    </p>

    {{-- ========= A11. Noise ========= --}}
    <p><strong>11. Noise:</strong> Quiet hours are from 9:30 PM to 8:00 AM. Noise complaints may result in eviction and
        a <strong>$200 fine</strong>.</p>

    {{-- ========= A12. Strata Compliance ========= --}}
    <p><strong>12. Strata Bylaws:</strong> Tenant agrees to comply with strata bylaws and pay for any fines related to
        non-compliance.
        Repeated violations may be deducted from the security deposit.</p>

    {{-- ========= A13. Email Notices ========= --}}
    <p><strong>13. Email:</strong> The tenant agrees to receive formal notices to the following address:
        <strong>{{ $lease->tenant->email ?? '[Tenant Email]' }}</strong>
    </p>



    <div class="signature">
        <table>
            <tr>
                <th>Landlord Signature</th>
                <th>Tenant Signature</th>
            </tr>
            <tr>
                <td><br><br>_______________________<br>{{ $lease->landlord->full_name ?? 'Sutton' }}</td>
                <td><br><br>_______________________<br>{{ $lease->tenant->full_name }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
