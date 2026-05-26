<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Cabeçalho da Página --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl text-gray-800 italic">Editar Registro</h2>
                    <p class="text-[#8C846C]/60 text-[10px] font-black uppercase tracking-widest font-sans mt-1">
                        Atualizando dados de: {{ $patient->name }}
                    </p>
                </div>
                <a href="{{ route('pacientes') }}" class="bg-white text-[#8C846C] border border-[#E1D3C1] px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-[#8C846C] hover:text-white transition-all flex items-center font-sans">
                    <span class="mr-2">←</span> Voltar
                </a>
            </div>

            {{-- Card de Edição --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-[2.5rem] border border-[#E1D3C1] p-10">
                <form action="{{ route('patients.update', $patient->id) }}" method="POST" class="space-y-8 font-sans">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Nome Completo --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Nome Completo *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $patient->name) }}" required 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- E-mail --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $patient->email) }}" 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- Telefone --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Telefone/WhatsApp</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $patient->phone) }}" maxlength="19"
                                placeholder="+55 (00) 00000-0000"
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- CPF --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Matrícula de Acesso *</label>
                            <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $patient->cpf) }}" required maxlength="14"
                                placeholder="000.000.000-00"
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700 @error('cpf') border-red-400 @enderror">
                            
                            @error('cpf')
                                <p class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Data de Nascimento --}}
                        <div>
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Data de Nascimento</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $patient->birth_date) }}" 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700 uppercase">
                        </div>

                        {{-- Cidade / Estado --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Cidade / Estado</label>
                            <input type="text" name="city_state" value="{{ old('city_state', $patient->city_state) }}" 
                                placeholder="Ex: São Paulo, SP" 
                                class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3 px-5 text-gray-700">
                        </div>

                        {{-- Observações --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Observações Clínicas</label>
                            <textarea name="observations" rows="4" 
                                class="w-full rounded-[2rem] border-[#E1D3C1] bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all p-6 text-gray-700">{{ old('observations', $patient->observations) }}</textarea>
                        </div>
                    </div>

                    {{-- Ações de Rodapé --}}
                    <div class="pt-8 border-t border-[#F9F6F3] flex justify-between items-center">
                        <button type="button" onclick="confirmDelete()" class="text-red-400 hover:text-red-600 text-[10px] font-black uppercase tracking-widest transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Excluir Paciente
                        </button>

                        <button type="submit" class="bg-[#8C846C] hover:bg-[#766f5a] text-white px-12 py-4 rounded-full font-black uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-[#8C846C]/20 transition-all transform hover:-translate-y-0.5 active:scale-95">
                            Salvar Alterações
                        </button>
                    </div>
                </form>

                {{-- Formulário de Exclusão Oculto --}}
                <form id="delete-form" action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

<script>
    // SweetAlert para Exclusão
    function confirmDelete() {
        Swal.fire({
            title: 'Excluir registro?',
            text: "Todos os dados de {{ $patient->name }} serão removidos permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8C846C',
            cancelButtonColor: '#E1D3C1',
            confirmButtonText: 'Sim, excluir permanentemente',
            cancelButtonText: 'Cancelar',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-[2.5rem] border border-[#E1D3C1] font-sans',
                title: 'font-serif italic text-gray-800',
                confirmButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3',
                cancelButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }

    // Máscaras de CPF e Telefone (Mantidas conforme o padrão luxury)
    document.getElementById('cpf').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.replace(/^(\d{3})(\d)/, '$1.$2');
        if (value.length > 6) value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
        if (value.length > 9) value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
        e.target.value = value;
    });

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