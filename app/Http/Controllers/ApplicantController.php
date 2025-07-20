<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with(['employmentDetail', 'rentalApplication.property']);

        // 角色控制：非管理员只能看到自己的申请人
        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        // 关键词搜索
        if ($keyword = $request->input('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('phone', 'like', "%$keyword%");
            });
        }

        $applicants = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('applicants.index', compact('applicants'));
    }

    public function create()
    {
        return view('applicants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            // ... 其他字段校验
        ]);

        $validated['user_id'] = auth()->id(); // 归属代理用户

        Applicant::create($validated);

        return redirect()->route('applicants.index')->with('success', '申请人创建成功');
    }

    public function show(Applicant $applicant)
    {
        $this->authorizeView($applicant);
        return view('applicants.show', compact('applicant'));
    }

    public function edit(Applicant $applicant)
    {
        $this->authorizeView($applicant);
        return view('applicants.edit', compact('applicant'));
    }

    public function update(Request $request, Applicant $applicant)
    {
        $this->authorizeView($applicant);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            // ... 其他字段校验
        ]);

        $applicant->update($validated);

        return redirect()->route('applicants.index')->with('success', '申请人更新成功');
    }

    public function destroy(Applicant $applicant)
    {
        $this->authorizeView($applicant);
        $applicant->delete();

        return redirect()->route('applicants.index')->with('success', '申请人已删除');
    }

    // 🔐 限制普通代理用户访问他人数据
    protected function authorizeView(Applicant $applicant)
    {
        if (!auth()->user()->hasRole('admin') && $applicant->user_id !== auth()->id()) {
            abort(403, '无权限访问此申请人记录');
        }
    }
}
