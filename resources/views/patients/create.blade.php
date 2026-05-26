<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Botão Voltar --}}
            <div class="mb-6">
                <a href="{{ route('pacientes') }}" class="text-[#8C846C] hover:text-[#766f5a] text-[10px] font-black uppercase tracking-[0.2em] flex items-center transition-all">
                    <span class="mr-2">←</span> Voltar para a listagem
                </a>
            </div>

            {{-- Card de Cadastro --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-[2.5rem] border border-[#E1D3C1] p-10">
                <div class="mb-10 text-left border-b border-[#F9F6F3] pb-6">
                    <h2 class="text-3xl text-gray-800 italic mb-2">Cadastrar Novo Paciente</h2>
                    <p class="text-[#8C846C]/60 text-[10px] font-black uppercase tracking-widest font-sans">Preencha os dados abaixo para o registro clínico</p>
                </div>

                <form action="{{ route('patients.store') }}" method="POST" class="space-y-8 font-sans">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Nome Completo --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Nome Completo *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- E-mail --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">E-mail</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- Telefone --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Telefone/WhatsApp</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" maxlength="19"
                                placeholder="+55 (00) 00000-0000"
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- CPF --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Matrícula de Acesso *</label>
                            <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}" required maxlength="14"
                                placeholder="000.000.000-00"
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700 @error('cpf') border-red-400 @enderror">
                            
                            {{-- Mensagem de erro para CPF duplicado --}}
                            @error('cpf')
                                <p class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Data de Nascimento --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Data de Nascimento</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700 uppercase">
                        </div>

                        {{-- Cidade / Estado --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Cidade / Estado</label>
                            <input type="text" name="city_state" value="{{ old('city_state') }}" placeholder="Ex: São Paulo, SP" 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- Observações --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Observações Clínicas (Opcional)</label>
                            <textarea name="observations" rows="4" 
                                class="w-full rounded-[2rem] border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all p-6 text-gray-700">{{ old('observations') }}</textarea>
                        </div>
                    </div>

                    {{-- Botão de Ação --}}
                    <div class="pt-8 flex justify-end">
                        <button type="submit" class="bg-[#8C846C] hover:bg-[#766f5a] text-white px-12 py-4 rounded-full font-black uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-[#8C846C]/20 transition-all transform hover:-translate-y-0.5 active:scale-95">
                            Finalizar Cadastro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    // Máscara de CPF (000.000.000-00)
    document.getElementById('cpf').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.replace(/^(\d{3})(\d)/, '$1.$2');
        if (value.length > 6) value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
        if (value.length > 9) value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
        e.target.value = value;
    });

    // Máscara de Telefone +55 (DDD) XXXXX-XXXX
    document.getElementById('phone').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (!value.startsWith('55')) value = '55' + value;
        let formatted = '+55 ';
        if (value.length > 2) formatted += '(' + value.substring(2, 4) + ') ';
        if (value.length > 4) formatted += value.substring(4, 9);
        if (value.length > 9) formatted += '-' + value.substring(9, 13);
        e.target.value = formatted;
    });
</script>
</x-app-layout>