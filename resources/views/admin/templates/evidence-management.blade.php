
@extends('layouts.admin')

@section('title', 'Evidence Management')

@section('content')
<div class="h-full flex flex-col">
    <div class="h-16 bg-[#0B0F19] border-b border-[#39FF14] flex items-center px-6">
        <span class="text-xl font-bold glow-green">Evidence Management System</span>
    </div>
    <div class="flex-1 grid grid-cols-4 gap-4 p-4">
        <div class="col-span-1 glass-card p-4">
            <h3 class="text-[#39FF14] mb-4">Cases</h3>
            <div class="space-y-2">
                <div class="p-3 bg-[rgba(57,255,20,0.1)] rounded cursor-pointer">
                    <div class="font-semibold">Case #2024-001</div>
                    <div class="text-sm text-gray-400">Breach Analysis</div>
                </div>
            </div>
        </div>
        <div class="col-span-3 glass-card p-4">
            <h3 class="text-[#00D4FF] mb-4">File Catalog</h3>
            <div class="grid grid-cols-4 gap-4">
                <div class="p-4 border border-[#00D4FF] rounded text-center">
                    <div class="text-3xl mb-2">📄</div>
                    <div class="text-sm">disk_image_01.dd</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
