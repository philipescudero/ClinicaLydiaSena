<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-5xl mx-auto px-4">
            
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl text-gray-800 italic">Gestão de Equipe</h2>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#8C846C]/60 font-sans mt-2">Administradores com acesso total ao sistema</p>
                </div>
                <a href="{{ route('settings.index') }}" class="bg-white text-[#8C846C] border border-[#E1D3C1] px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-[#8C846C] hover:text-white transition-all font-sans">
                    ← Voltar
                </a>
            </div>

            {{-- Alertas de Erro ou Sucesso no Topo --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-[10px] font-sans font-bold uppercase tracking-wider rounded-r-xl">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 text-[10px] font-sans font-bold uppercase tracking-wider rounded-r-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Coluna da Esquerda: Formulário de Novo Acesso --}}
                <div class="md:col-span-1">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-[#E1D3C1] shadow-sm">
                        <h3 class="text-[11px] font-black uppercase tracking-widest text-gray-800 font-sans mb-6 text-center">Novo Acesso Full</h3>
                        
                        <form action="{{ route('settings.admins.store') }}" method="POST" class="space-y-4 font-sans">
                            @csrf
                            <div>
                                <label class="text-[9px] uppercase font-black text-[#8C846C]/60 ml-2">Nome Completo</label>
                                <input type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3]/50 text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>
                            <div>
                                <label class="text-[9px] uppercase font-black text-[#8C846C]/60 ml-2">E-mail</label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3]/50 text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>
                            <div>
                                <label class="text-[9px] uppercase font-black text-[#8C846C]/60 ml-2">Senha</label>
                                <input type="password" name="password" required class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3]/50 text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>
                            <button type="submit" class="w-full bg-[#8C846C] text-white py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-[#8C846C]/20 mt-4 hover:-translate-y-0.5 transition-all">
                                Conceder Acesso
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Coluna da Direita: Lista de Admins Atuais --}}
                <div class="md:col-span-2">
                    <div class="bg-white p-8 rounded-[3rem] border border-[#E1D3C1] shadow-sm min-h-full">
                        <h3 class="text-[11px] font-black uppercase tracking-widest text-gray-800 font-sans mb-6">Administradores Ativos</h3>

                        <div class="space-y-4 font-sans">
                            @foreach($admins as $admin)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-[#F9F6F3]/50 border border-[#E1D3C1]/30">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-[#8C846C] flex items-center justify-center text-white font-black text-xs uppercase shadow-sm">
                                        {{ substr($admin->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black text-gray-800 uppercase tracking-tight">{{ $admin->name }}</p>
                                        <p class="text-[10px] text-[#8C846C]/60">{{ $admin->email }}</p>
                                        <p class="text-[8px] text-[#8C846C]/40 uppercase font-bold mt-1 tracking-tighter italic">
                                            Acesso em: {{ $admin->created_at->format('d/m/Y \à\s H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    @if($admin->id !== Auth::id())
                                        {{-- Botão estilizado com ícone discreto --}}
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $admin->id }}', '{{ $admin->name }}')"
                                                class="group flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] uppercase font-black tracking-[0.1em] text-red-400/50 hover:text-red-600 hover:bg-red-50 transition-all duration-300">
                                            <span>Remover Acesso</span>
                                            <svg class="w-3.5 h-3.5 opacity-30 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        {{-- Formulário oculto para o envio --}}
                                        <form id="delete-form-{{ $admin->id }}" action="{{ route('settings.admins.destroy', $admin->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <span class="text-[9px] font-black uppercase text-[#8C846C]/30 tracking-widest italic px-4 select-none">Você</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Revogar Acesso?',
        html: `Você está prestes a remover o acesso administrativo de <br><b>${name}</b>.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, Revogar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#8C846C',
        cancelButtonColor: '#E1D3C1',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-[2.5rem] border border-[#E1D3C1] font-sans',
            title: 'font-serif italic text-gray-800 text-2xl',
            htmlContainer: 'text-[11px] text-[#8C846C] uppercase tracking-wide mt-2',
            confirmButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-8 py-4',
            cancelButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-8 py-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>
</x-app-layout>