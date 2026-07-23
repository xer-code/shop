<div class="fade-in space-y-6">
    <!-- Reports Generator Control -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
        <h3 class="text-base font-bold text-white mb-1">Generate Enterprise Report</h3>
        <p class="text-xs text-gray-500 mb-6">Select report parameters and choose output formats</p>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Report Classification</label>
                <select id="rep_type" class="select-dark w-full text-xs">
                    <option value="sales">Sales & Financial Statements</option>
                    <option value="inventory">Inventory Valuation Ledger</option>
                    <option value="users">User Access & Security Audits</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Billing Cycle</label>
                <select id="rep_cycle" class="select-dark w-full text-xs">
                    <option value="mtd">Month to Date (MTD)</option>
                    <option value="ytd">Year to Date (YTD)</option>
                    <option value="custom">Custom Billing Range</option>
                </select>
            </div>
            <div class="flex items-end gap-2 md:col-span-2">
                <button onclick="alert('Exporting data matrix...');" class="btn-gold text-xs py-2 px-4 rounded w-full justify-center">
                    📊 Generate Report Grid
                </button>
                <button onclick="alert('Downloading CSV...');" class="px-4 py-2 bg-[#222] border border-[#2a2a2a] hover:border-[#D4A017] text-white text-xs font-bold rounded transition-all">
                    CSV
                </button>
                <button onclick="alert('Downloading PDF...');" class="px-4 py-2 bg-[#222] border border-[#2a2a2a] hover:border-[#D4A017] text-white text-xs font-bold rounded transition-all">
                    PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Generated Data Preview -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 space-y-4">
        <h3 class="text-base font-bold text-white">Preview Data Summary</h3>
        <p class="text-xs text-gray-500 mb-4">Sample summary data preview for generated report</p>

        <div class="border border-[#2a2a2a] rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#111] border-b border-[#2a2a2a] text-xs font-bold text-gray-400 uppercase">
                        <th class="p-3">Data Point Metric</th>
                        <th class="p-3 text-right">Value Record</th>
                        <th class="p-3 text-right">Deviation Rate</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-300 divide-y divide-[#2a2a2a]">
                    <tr>
                        <td class="p-3 font-semibold text-white">Gross Merchandise Value (GMV)</td>
                        <td class="p-3 text-right font-mono font-bold text-green-500">$135,462.50</td>
                        <td class="p-3 text-right font-mono font-bold text-green-500">+14.2%</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-semibold text-white">Transaction Tax Collected</td>
                        <td class="p-3 text-right font-mono">$10,612.00</td>
                        <td class="p-3 text-right font-mono font-bold text-green-500">+8.5%</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-semibold text-white">Total Discounts Availed</td>
                        <td class="p-3 text-right font-mono text-red-500">-$6,420.00</td>
                        <td class="p-3 text-right font-mono font-bold text-red-500">-2.1%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
