<?php

namespace App\Http\Controllers;

use App\Models\RentalApplication;
use App\Models\Applicant;
use App\Models\Consent;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\EmploymentDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class RentalApplicationController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $filterStatus = $request->input('filter_values.status');

        $sortField = $request->input('sort', 'submitted_at'); // 默认按提交时间
        $direction = $request->input('direction', 'desc');    // 默认倒序

        $rentalApplications = RentalApplication::query()
            ->with(['property', 'employment', 'applicant', 'reviewer'])
            ->when($keyword, fn($q) => $q->where(function ($query) use ($keyword) {
                $query->where('application_code', 'like', "%{$keyword}%")
                    ->orWhere('notes', 'like', "%{$keyword}%");
            }))
            ->when($filterStatus, fn($q) => $q->where('status', $filterStatus))
            ->when($sortField && in_array($sortField, ['application_code', 'submitted_at', 'updated_at', 'status']), function ($q) use ($sortField, $direction) {
                $q->orderBy($sortField, $direction);
            })
            ->paginate(10);

        return view('rental_applications.index', compact('rentalApplications'));
    }


    public function create()
    {
        $properties = Property::whereNull('deleted_at')->get(); // 仅列出有效房源
        return view('rental_applications.create', compact('properties'));
    }

    public function createFromProperty($id)
    {
        $property = Property::findOrFail($id); // ✅ 正确：赋值给 $property
        $properties = Property::whereNull('deleted_at')->get(); 

        return view('rental_applications.create', compact('property', 'properties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required',
            'application_code' => 'required|unique:rental_applications',
            'fair_housing_acknowledged' => 'accepted',
            'fingerprint' => 'nullable|string',

            // Applicant
            'applicant.full_name' => 'required|string',
            'applicant.email' => 'required|email',
            'applicant.phone' => 'nullable|string',
            'applicant.date_of_birth' => 'nullable|date',
            'applicant.government_id_type' => 'nullable|string',
            'applicant.ssn_last4' => 'nullable|string',
            'applicant.address_line1' => 'nullable|string',
            'applicant.address_line2' => 'nullable|string',
            'applicant.city' => 'nullable|string',
            'applicant.state' => 'nullable|string',
            'applicant.zip_code' => 'nullable|string',
            'applicant.country' => 'nullable|string',
            'applicant.emergency_contact_name' => 'nullable|string',
            'applicant.emergency_contact_phone' => 'nullable|string',
            'applicant.renters_insurance_provider' => 'nullable|string',
            'applicant.policy_number' => 'nullable|string',
            'applicant.coverage_amount' => 'nullable|string',

            // Employment
            'employment.employer_name' => 'nullable|string',
            'employment.job_title' => 'nullable|string',
            'employment.monthly_income' => 'nullable|numeric',
            'employment.other_income_source' => 'nullable|string',
            'employment.income_verified_by' => 'nullable|string',
            'employment.verification_date' => 'nullable|date',

            // Consent
            'consent.credit_check_consent' => 'nullable|boolean',
            'consent.background_check_consent' => 'nullable|boolean',
            'consent.esignature_provider' => 'nullable|string',
            'consent.esignature_id' => 'nullable|string',

            //attachments
            'attachments' => 'nullable|string',
        ]);

        $application = DB::transaction(function () use ($data) {
            // 1. 创建 RentalApplication 主表
            $application = RentalApplication::create([
                'property_id' => $data['property_id'],
                'application_code' => $data['application_code'],
                'fair_housing_acknowledged' => $data['fair_housing_acknowledged'],
                'submitted_at' => now(),
                'last_accessed_at' => now(),
            ]);

            // 2. create Applicant
            $applicantData = $data['applicant'] ?? [];
            $applicantData['rental_application_id'] = $application->id;
            $applicant = Applicant::create($applicantData);

            // ✅ 3. create EmploymentDetail（需要 applicant_id）
            $employmentData = $data['employment'] ?? [];
            $employmentData['applicant_id'] = $applicant->id;
            EmploymentDetail::create($employmentData);

            // 4. create Consent
            $consentData = $data['consent'] ?? [];
            $consentData['rental_application_id'] = $application->id;
            $consentData['credit_check_consent'] = $consentData['credit_check_consent'] ?? false;
            $consentData['background_check_consent'] = $consentData['background_check_consent'] ?? false;
            Consent::create($consentData);

            // 5. create attachment
            $attachmentsJson = request('attachments');
            if ($attachmentsJson) {
                $attachments = json_decode($attachmentsJson, true);

                foreach ($attachments as $file) {
                    $application->files()->create([
                        'title'        => $file['title'] ?? null,
                        'filename'        => $file['filename'] ?? null,
                        'path'            => $file['path'] ?? null,
                        'mime_type'       => $file['mime_type'] ?? null,
                        'size'            => $file['size'] ?? null,
                        'disk'            => $file['disk'] ?? 'public',
                        'category'             => $file['category'] ?? null,
                        'description'     => $file['description'] ?? null,
                        'is_cover'        => $file['is_cover'] ?? false,
                        'is_private'      => $file['is_private'] ?? false,
                        'sort_order'      => $file['sort_order'] ?? 0,
                        'uploaded_by'     => auth()->id(), // 可选
                    ]);
                }
            }
            return $application;
        });

        // return redirect()->route('rental_applications.index')->with('success', 'Application created successfully.');
        return redirect()->route('rental_applications.show',$application)->with('success', 'Application created successfully.');
    }


    public function update(Request $request, RentalApplication $rentalApplication): RedirectResponse
    {
        $data = $request->validate([
            'property_id' => 'required',
            'application_code' => 'required|string|unique:rental_applications,application_code,' . $rentalApplication->id,
            'fair_housing_acknowledged' => 'required|accepted',

            'applicant.full_name' => 'required|string',
            'applicant.email' => 'required|email',
            'applicant.phone' => 'nullable|string',
            'applicant.date_of_birth' => 'required|date',
            'applicant.government_id_type' => 'nullable|string',
            'applicant.ssn_last4' => 'nullable|string',
            'applicant.address_line1' => 'nullable|string',
            'applicant.address_line2' => 'nullable|string',
            'applicant.city' => 'nullable|string',
            'applicant.state' => 'nullable|string',
            'applicant.zip_code' => 'nullable|string',
            'applicant.country' => 'nullable|string',
            'applicant.emergency_contact_name' => 'nullable|string',
            'applicant.emergency_contact_phone' => 'nullable|string',
            'applicant.renters_insurance_provider' => 'nullable|string',
            'applicant.policy_number' => 'nullable|string',
            'applicant.coverage_amount' => 'nullable|numeric',

            'employment.employer_name' => 'nullable|string',
            'employment.job_title' => 'nullable|string',
            'employment.monthly_income' => 'nullable|numeric',
            'employment.other_income_source' => 'nullable|string',
            'employment.income_verified_by' => 'nullable|string',
            'employment.verification_date' => 'nullable|date',

            'consent.credit_check_consent' => 'nullable|boolean',
            'consent.background_check_consent' => 'nullable|boolean',
            'consent.esignature_provider' => 'nullable|string',
            'consent.esignature_id' => 'nullable|string',

            'attachments' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $rentalApplication) {
            $rentalApplication->update([
                'property_id' => $data['property_id'],
                'application_code' => $data['application_code'],
                'fair_housing_acknowledged' => $data['fair_housing_acknowledged'],
                'last_accessed_at' => now(),
            ]);

            $rentalApplication->applicant()->updateOrCreate(
                ['rental_application_id' => $rentalApplication->id],
                $data['applicant']
            );

            // $rentalApplication->employmentDetail()->updateOrCreate(
            //     ['rental_application_id' => $rentalApplication->id],
            //     $data['employment']
            // );

            $applicant = $rentalApplication->applicant()->firstOrCreate(
                ['rental_application_id' => $rentalApplication->id],
                $data['applicant']
            );

            $applicant->employmentDetail()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                $data['employment']
            );

            $consent = $data['consent'];
            $consent['credit_check_consent'] = $consent['credit_check_consent'] ?? false;
            $consent['background_check_consent'] = $consent['background_check_consent'] ?? false;

            $rentalApplication->consent()->updateOrCreate(
                ['rental_application_id' => $rentalApplication->id],
                $consent
            );

            if (!empty($data['attachments'])) {
                $attachments = json_decode($data['attachments'], true);

                $existingFiles = $rentalApplication->files()->get()->keyBy('id');
                $processedIds = [];

                foreach ($attachments as $index => $file) {
                    if (!empty($file['id']) && $existingFiles->has($file['id'])) {
                        // ✅ 更新已有附件
                        $existingFiles[$file['id']]->update([
                            'title' => $file['title'] ?? null,
                            'description' => $file['description'] ?? null,
                            'category' => $file['category'] ?? null,
                            'is_cover' => $file['is_cover'] ?? false,
                            'is_private' => $file['is_private'] ?? false,
                            'sort_order' => $index + 1,
                        ]);
                        $processedIds[] = $file['id'];
                    } else {
                        // ✅ 新附件
                        //dd($file);
                        // try {
                        //     $newFile = $rentalApplication->files()->create([
                        //         // 'title'    => $file['title'],
                        //         'filename'    => $file['filename'],
                        //         'path'        => $file['path'],
                        //         'mime_type'   => $file['mime_type'],
                        //         'size'        => $file['size'],
                        //         'disk'        => $file['disk'] ?? 'public',
                        //         'tag'         => $file['category'] ?? null,
                        //         'description' => $file['description'] ?? null,
                        //         'is_cover'    => $file['is_cover'] ?? false,
                        //         'is_private'  => $file['is_private'] ?? false,
                        //         'sort_order'  => $index + 1,
                        //         'uploaded_by' => auth()->id(),
                        //     ]);

                        //     //dd($newFile->id);
                        //     if (!$newFile || !$newFile->id) {
                        //         dd('创建失败但未抛异常', $newFile);
                        //     }
                        // } catch (\Exception $e) {
                        //     dd('插入失败', $e->getMessage());
                        // }
                        $new = File::create([
                            'title' => $file['title'],
                            'filename' => $file['filename'],
                            'path' => $file['path'],
                            'mime_type' => $file['mime_type'],
                            'size' => $file['size'],
                            'disk' => $file['disk'] ?? 'public',
                            'fileable_type' => RentalApplication::class,
                            'fileable_id' => $rentalApplication->id,
                            'category' => $file['category'] ?? null,
                            'description' => $file['description'] ?? null,
                            'is_cover' => $file['is_cover'] ?? false,
                            'is_private' => $file['is_private'] ?? false,
                            'sort_order' => $index + 1,
                            'uploaded_by' => auth()->id(),
                        ]);
                        $processedIds[] = $new->id;
                    }
                }

                // ✅ 删除未在附件中提交的文件（可选）
                $rentalApplication->files()
                    ->whereNotIn('id', $processedIds)
                    ->delete();
            }
        });

        // return redirect()->route('rental_applications.index')->with('success', 'Application updated successfully.');
        return redirect()->route('rental_applications.show', ['rental_application' => $rentalApplication->id])->with('success', 'Application updated successfully.');
    }



    public function show(RentalApplication $rentalApplication)
    {
        $rentalApplication->load([
            'applicants.employmentDetail',
            'consent',
            'reviewer', // optional: 用户模型的 reviewed_by 外键关联
            'files',
            'files.uploader',
        ]);

        // $attachmentsJson = $rentalApplication->files->map(function ($f) {
        //     return [
        //         'id' => $f->id,
        //         'title' => $f->title,
        //         'filename' => $f->filename,
        //         'path' => $f->path,
        //         'category' => $f->tag ?? 'uncategorized',
        //         'description' => $f->description,
        //         'is_cover' => $f->is_cover,
        //         'mime_type' => $f->mime_type,
        //         'size' => $f->size,
        //         'disk' => $f->disk,
        //         'fileable_type' => $f->fileable_type,
        //         'fileable_id' => $f->fileable_id,
        //         'uploaded_by' => $f->uploaded_by,
        //         'created_at' => $f->created_at->toDateTimeString(),
        //     ];
        // });

        $attachments = $rentalApplication->files->map(function ($file) {
            return [
                'id' => $file->id,
                'title' => $file->title,
                'filename' => $file->filename,
                'path' => $file->path,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'disk' => $file->disk,
                'category' => $file->category,
                'description' => $file->description,
                'is_cover' => $file->is_cover,
                'is_private' => $file->is_private,
                'sort_order' => $file->sort_order,
                'fileable_type' => $file->fileable_type,
                'created_at' => optional($file->created_at)->toDateTimeString(),
                'uploaded_by' => $file->uploader->name ?? 'Unknown',
            ];
        });

        return view('rental_applications.show', compact('rentalApplication', 'attachments'));
    }

    public function edit(RentalApplication $rentalApplication)
    {
        $rentalApplication->load(['applicant', 'employment', 'consent', 'files', 'files.uploader']); // 👈 加上这行
        $properties = Property::all();
        $attachments = $rentalApplication->files->map(function ($file) {
            //dd(optional($file->created_at)->toDateTimeString());
            return [
                'id' => $file->id,
                'title' => $file->title,
                'filename' => $file->filename,
                'path' => $file->path,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'disk' => $file->disk,
                'category' => $file->category,
                'description' => $file->description,
                'is_cover' => $file->is_cover,
                'is_private' => $file->is_private,
                'sort_order' => $file->sort_order,
                'fileable_type' => $file->fileable_type,
                'created_at' => optional($file->created_at)->toDateTimeString(),
                'uploaded_by' => $file->uploader->name ?? 'Unknown',
            ];
        });

        return view('rental_applications.edit', [
            'application' => $rentalApplication,
            'properties' => $properties,
            'isEdit' => true,
            'formAction' => route('rental_applications.update', $rentalApplication->id),
            'attachmentsJson' => $attachments,
        ]);
    }

    public function destroy(RentalApplication $rentalApplication)
    {
        $rentalApplication->delete();
        return redirect()->route('rental_applications.index')->with('success', 'Application deleted.');
    }

    public function batchDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        RentalApplication::whereIn('id', $ids)->delete();

        return redirect()->route('rental_applications.index')->with('success', '批量删除成功');
    }

    // 批量审核通过
    public function batchApprove(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        RentalApplication::whereIn('id', $ids)->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        return response()->json(['success' => true, 'message' => '批量审核通过成功']);
    }

    // 批量拒绝
    public function batchReject(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        RentalApplication::whereIn('id', $ids)->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        return response()->json(['success' => true, 'message' => '批量拒绝成功']);
    }

    // 导出 CSV
    public function export(Request $request)
    {
        $applications = RentalApplication::with(['applicant', 'reviewer'])->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rental_applications.csv"',
        ];

        $callback = function () use ($applications) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', '编号', '状态', '申请人', '邮箱', '提交时间', '审核人', '备注']);

            foreach ($applications as $app) {
                fputcsv($handle, [
                    $app->id,
                    $app->application_code,
                    $app->status,
                    $app->applicant->full_name ?? '',
                    $app->applicant->email ?? '',
                    $app->submitted_at,
                    $app->reviewer->name ?? '',
                    $app->review_notes,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 更新备注
    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'review_notes' => 'nullable|string'
        ]);

        $application = RentalApplication::findOrFail($id);
        $application->notes = $request->input('review_notes');
        $application->reviewed_by = auth()->id();
        $application->reviewed_at = now();
        $application->save();

        return response()->json(['success' => true]);
    }

    // RentalApplicationController
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'review_notes' => 'nullable|string',
        ]);

        $app = RentalApplication::findOrFail($id);

        $oldNote = trim($app->notes ?? '');
        $newNote = trim($request->review_notes ?? '');
        $timestamp = now()->format('Y-m-d H:i');

        // 如果有新的备注，就追加带时间戳的备注
        if ($newNote !== '') {
            $combinedNote = $oldNote
                ? $oldNote . "\n\n[{$timestamp}] " . $newNote
                : "[{$timestamp}] " . $newNote;
            $app->notes = $combinedNote;
        }

        $app->status = $request->status;
        $app->reviewed_by = auth()->id(); // 审核人
        $app->reviewed_at = now();        // 审核时间
        $app->save();

        return response()->json(['success' => true]);
    }
}
