<div class="fade-in space-y-8">
    <!-- Top KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="admin-stat-card bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 relative overflow-hidden group hover:border-[#D4A017] transition-all">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl">📈</div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Net Sales Revenue</div>
            <div class="text-3xl font-extrabold text-[#D4A017]">$124,850.00</div>
            <div class="text-xs text-green-500 mt-2 flex items-center gap-1 font-semibold">
                <span>↑ 18.2%</span> <span class="text-gray-600">vs last month</span>
            </div>
        </div>
        
        <div class="admin-stat-card bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 relative overflow-hidden group hover:border-[#D4A017] transition-all">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl">🛒</div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Conversion Rate</div>
            <div class="text-3xl font-extrabold text-[#D4A017]">3.42%</div>
            <div class="text-xs text-green-500 mt-2 flex items-center gap-1 font-semibold">
                <span>↑ 0.8%</span> <span class="text-gray-600">vs last week</span>
            </div>
        </div>

        <div class="admin-stat-card bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 relative overflow-hidden group hover:border-[#D4A017] transition-all">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl">👥</div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Customer Lifetime</div>
            <div class="text-3xl font-extrabold text-[#D4A017]">$842.10</div>
            <div class="text-xs text-red-500 mt-2 flex items-center gap-1 font-semibold">
                <span>↓ 1.4%</span> <span class="text-gray-600">due to discount season</span>
            </div>
        </div>

        <div class="admin-stat-card bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 relative overflow-hidden group hover:border-[#D4A017] transition-all">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl">🛍️</div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Average Order Value</div>
            <div class="text-3xl font-extrabold text-[#D4A017]">$189.50</div>
            <div class="text-xs text-green-500 mt-2 flex items-center gap-1 font-semibold">
                <span>↑ 4.2%</span> <span class="text-gray-600">from bulk purchases</span>
            </div>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales performance bar graph (pure CSS / Flexbox) -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Monthly Sales Performance</h3>
                    <p class="text-xs text-gray-500">Gross revenue breakdown by month</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-[#111] border border-[#2a2a2a] text-xs rounded text-gray-400 font-semibold cursor-pointer hover:border-[#D4A017]">Export CSV</span>
                </div>
            </div>
            
            <div class="h-64 flex items-end justify-between gap-3 pt-6 border-b border-[#2a2a2a]">
                <!-- Jan -->
                <div class="flex-1 flex flex-col items-center group h-full justify-end">
                    <div class="text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-bold">$12k</div>
                    <div class="w-full bg-[#2a2a2a] group-hover:bg-[#D4A017] rounded-t transition-all" style="height: 35%;"></div>
                    <span class="text-[11px] text-gray-400 mt-2">Jan</span>
                </div>
                <!-- Feb -->
                <div class="flex-1 flex flex-col items-center group h-full justify-end">
                    <div class="text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-bold">$18k</div>
                    <div class="w-full bg-[#2a2a2a] group-hover:bg-[#D4A017] rounded-t transition-all" style="height: 48%;"></div>
                    <span class="text-[11px] text-gray-400 mt-2">Feb</span>
                </div>
                <!-- Mar -->
                <div class="flex-1 flex flex-col items-center group h-full justify-end">
                    <div class="text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-bold">$15k</div>
                    <div class="w-full bg-[#2a2a2a] group-hover:bg-[#D4A017] rounded-t transition-all" style="height: 40%;"></div>
                    <span class="text-[11px] text-gray-400 mt-2">Mar</span>
                </div>
                <!-- Apr -->
                <div class="flex-1 flex flex-col items-center group h-full justify-end">
                    <div class="text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-bold">$22k</div>
                    <div class="w-full bg-[#2a2a2a] group-hover:bg-[#D4A017] rounded-t transition-all" style="height: 60%;"></div>
                    <span class="text-[11px] text-gray-400 mt-2">Apr</span>
                </div>
                <!-- May -->
                <div class="flex-1 flex flex-col items-center group h-full justify-end">
                    <div class="text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-bold">$28k</div>
                    <div class="w-full bg-[#2a2a2a] group-hover:bg-[#D4A017] rounded-t transition-all" style="height: 75%;"></div>
                    <span class="text-[11px] text-gray-400 mt-2">May</span>
                </div>
                <!-- Jun -->
                <div class="flex-1 flex flex-col items-center group h-full justify-end">
                    <div class="text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-bold">$35k</div>
                    <div class="w-full bg-[#2a2a2a] group-hover:bg-[#D4A017] rounded-t transition-all" style="height: 90%;"></div>
                    <span class="text-[11px] text-gray-400 mt-2">Jun</span>
                </div>
                <!-- Jul -->
                <div class="flex-1 flex flex-col items-center group h-full justify-end">
                    <div class="text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-bold">$42k</div>
                    <div class="w-full bg-[#D4A017] rounded-t transition-all" style="height: 100%;"></div>
                    <span class="text-[11px] text-[#D4A017] font-semibold mt-2">Jul</span>
                </div>
            </div>
        </div>

        <!-- Sales by Device category (Donut Chart visual mock) -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-2">Device Breakdown</h3>
            <p class="text-xs text-gray-500 mb-6">User devices accessing the shop</p>
            
            <div class="flex flex-col items-center justify-center pt-2">
                <!-- SVG Donut Chart -->
                <div class="relative w-40 h-40">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <!-- Background -->
                        <path class="text-[#2a2a2a]" stroke="currentColor" stroke-width="4" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Mobile Segment (60%) -->
                        <path class="text-[#D4A017]" stroke="currentColor" stroke-width="4" stroke-dasharray="60, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Desktop Segment (30%) starting at offset 60 -->
                        <path class="text-[#8b5cf6]" stroke="currentColor" stroke-width="4" stroke-dasharray="30, 100" stroke-dashoffset="-60" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Tablet Segment (10%) starting at offset 90 -->
                        <path class="text-[#3b82f6]" stroke="currentColor" stroke-width="4" stroke-dasharray="10, 100" stroke-dashoffset="-90" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-2xl font-black text-white">60%</span>
                        <span class="text-[9px] uppercase tracking-wider text-gray-500 font-bold">Mobile First</span>
                    </div>
                </div>

                <div class="w-full grid grid-cols-3 gap-2 text-center mt-6">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 font-semibold flex items-center justify-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#D4A017] inline-block"></span> Mobile
                        </span>
                        <span class="text-sm font-bold text-white">60%</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 font-semibold flex items-center justify-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#8b5cf6] inline-block"></span> Desktop
                        </span>
                        <span class="text-sm font-bold text-white">30%</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 font-semibold flex items-center justify-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#3b82f6] inline-block"></span> Tablet
                        </span>
                        <span class="text-sm font-bold text-white">10%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Country Performance & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Top Regions & Markets</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300 font-semibold">🇺🇸 United States</span>
                        <span class="text-[#D4A017] font-bold">$42,500.00 (34%)</span>
                    </div>
                    <div class="w-full bg-[#2a2a2a] h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-[#D4A017] to-[#E8C158] h-full" style="width: 34%;"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300 font-semibold">🇬🇧 United Kingdom</span>
                        <span class="text-[#D4A017] font-bold">$28,900.00 (23%)</span>
                    </div>
                    <div class="w-full bg-[#2a2a2a] h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-[#D4A017] to-[#E8C158] h-full" style="width: 23%;"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300 font-semibold">🇩🇪 Germany</span>
                        <span class="text-[#D4A017] font-bold">$18,400.00 (15%)</span>
                    </div>
                    <div class="w-full bg-[#2a2a2a] h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-[#D4A017] to-[#E8C158] h-full" style="width: 15%;"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300 font-semibold">🇯🇵 Japan</span>
                        <span class="text-[#D4A017] font-bold">$16,200.00 (13%)</span>
                    </div>
                    <div class="w-full bg-[#2a2a2a] h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-[#D4A017] to-[#E8C158] h-full" style="width: 13%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Top-Selling Products</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-[#111] rounded-lg border border-[#2a2a2a]">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">💻</span>
                        <div>
                            <div class="text-sm font-bold text-white">MacBook Pro 16" M3 Max</div>
                            <div class="text-xs text-gray-500">284 units sold</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-[#D4A017]">$993,716.00</div>
                        <div class="text-[10px] text-green-500 font-bold">Best seller</div>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-[#111] rounded-lg border border-[#2a2a2a]">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📱</span>
                        <div>
                            <div class="text-sm font-bold text-white">iPhone 15 Pro Max</div>
                            <div class="text-xs text-gray-500">562 units sold</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-[#D4A017]">$673,838.00</div>
                        <div class="text-[10px] text-green-500 font-bold">High stock turnover</div>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-[#111] rounded-lg border border-[#2a2a2a]">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🎧</span>
                        <div>
                            <div class="text-sm font-bold text-white">Sony WH-1000XM5</div>
                            <div class="text-xs text-gray-500">321 units sold</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-[#D4A017]">$111,708.00</div>
                        <div class="text-[10px] text-green-500 font-bold">Top accessories item</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
