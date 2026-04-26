
@extends('layouts.admin')

@section('title', 'Signal Analyzer')

@section('content')
<div class="h-full flex flex-col">
    <div class="h-16 bg-[#0B0F19] border-b border-[#6366F1] flex items-center px-6">
        <span class="text-xl font-bold text-[#6366F1]">GSM Signal Analyzer</span>
    </div>
    <div class="flex-1 p-4">
        <div class="grid grid-cols-4 gap-4 h-full">
            <div class="col-span-3 glass-card p-4">
                <canvas id="spectrumCanvas"></canvas>
            </div>
            <div class="glass-card p-4">
                <h3 class="text-[#6366F1] mb-4">Signal Strength</h3>
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-[#00D4FF">87%</div>
                        <div class="text-sm text-gray-400">Cell 1</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
