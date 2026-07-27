<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdInquiry;

class InquiryController extends Controller
{
    public function index()
    {
        return view('admin.inquiries.index', [
            'inquiries' => AdInquiry::orderByDesc('created_at')->paginate(25),
        ]);
    }

    public function toggleRead(AdInquiry $inquiry)
    {
        $inquiry->update(['is_read' => ! $inquiry->is_read]);

        return redirect()->route('admin.inquiries.index')
            ->with('success', $inquiry->is_read ? 'Marked as read.' : 'Marked as unread.');
    }

    public function destroy(AdInquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted.');
    }
}
