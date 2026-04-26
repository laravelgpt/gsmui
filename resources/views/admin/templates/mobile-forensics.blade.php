
@extends('layouts.admin')

@section('title', 'Mobile Forensics')

@section('content')
<div class="h-full flex flex-col">
    <div class="h-16 bg-[#0B0F19] border-b border-[#39FF14] flex items-center px-6">
        <span class="text-xl font-bold glow-green">Mobile Forensics Workstation</span>
    </div>
    <div class="flex-1 p-4">
        <div class="grid grid-cols-3 gap-4 h-full">
            <div class="glass-card p-4">
                <h3 class="text-[#39FF14] mb-4">Extraction Progress</h3>
                <div class="flex items-center justify-center">
                    <div class="w-32 h-32 rounded-full border-8 border-[#131828] border-t-[#39FF14] animate-spin"></div>
                </div>
            </div>
            <div class="col-span-2 glass-card p-4">
                <h3 class="text-[#00D4FF] mb-4">Data Categories</h3>
                <div class="flex gap-4">
                    <span class="badge badge-premium">SMS</span>
                    <span class="badge badge-premium">Contacts</span>
                    <span class="badge badge-premium">Photos</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
