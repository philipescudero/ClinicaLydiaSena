<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-3xl mx-auto px-4">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="text-3xl text-gray-800 italic">Dados da Clínica</h2>
                <a href="{{ route('settings.index') }}" class="bg-white text-[#8C846C] border border-[#E1D3C1] px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-[#8C846C] hover:text-white transition-all font-sans">
                    ← Voltar
                </a>
            </div>

            <div class="bg-white p-10 rounded-[3rem] border border-[#E1D3C1] shadow-sm">
                <form action="{{ route('settings.clinic.update') }}" method="POST" class="space-y-6 font-sans">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Telefone / WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $clinicData->phone) }}" 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-[#8C846C] focus:border-[#8C846C]">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Instagram (@)</label>
                            <input type="text" name="instagram" value="{{ old('instagram', $clinicData->instagram) }}" 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">WhatsApp Link</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $clinicData->whatsapp) }}" 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Rodapé de Documentos (PDF)</label>
                            <textarea name="footer_text" rows="3" 
                                class="w-full rounded-[2rem] border-[#E1D3C1] bg-[#F9F6F3]/50">{{ old('footer_text', $clinicData->footer_text) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#8C846C] text-white py-4 rounded-full font-black uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-[#8C846C]/20 transition-all hover:-translate-y-0.5">
                        Salvar Alterações
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>