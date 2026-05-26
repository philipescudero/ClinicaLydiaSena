<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Cabeçalho --}}
            <div class="mb-12 border-b border-[#E1D3C1] pb-8">
                <h2 class="text-4xl text-gray-800 italic">Configurações do Sistema</h2>
                <p class="text-[#8C846C]/60 text-[10px] font-black uppercase tracking-[0.3em] mt-3 font-sans">Gestão administrativa e segurança global</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Card 1: Alteração de Senha Global (Pacientes) --}}
                <div class="bg-white p-10 rounded-[3rem] border border-[#E1D3C1] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-[#FDF2F4] rounded-2xl flex items-center justify-center text-xl mb-6">🔑</div>
                        <h3 class="text-[11px] font-black uppercase tracking-widest text-gray-800 font-sans">Senha Padrão</h3>
                        <p class="text-[10px] text-gray-400 mt-2 leading-relaxed italic">
                            Altera a senha de acesso de **todos** os pacientes simultaneamente.
                        </p>
                    </div>
                    <button onclick="openGlobalPasswordModal()" class="mt-8 w-full border border-[#8C846C] text-[#8C846C] py-3 rounded-xl font-black uppercase text-[9px] tracking-widest hover:bg-[#8C846C] hover:text-white transition-all">
                        Personalizar Senha
                    </button>
                </div>

                {{-- Card 2: Backup do Sistema --}}
                <div class="bg-white p-10 rounded-[3rem] border border-[#E1D3C1] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-[#F3F9F6] rounded-2xl flex items-center justify-center text-xl mb-6">💾</div>
                        <h3 class="text-[11px] font-black uppercase tracking-widest text-gray-800 font-sans">Backup de Dados</h3>
                        <p class="text-[10px] text-gray-400 mt-2 leading-relaxed italic">
                            Gere uma cópia de segurança de todos os pacientes, prontuários e registros financeiros.
                        </p>
                    </div>
                    <a href="{{ route('settings.backup') }}" class="mt-8 w-full border border-[#8C846C] text-[#8C846C] py-3 rounded-xl font-black uppercase text-[9px] tracking-widest hover:bg-[#8C846C] hover:text-white transition-all text-center">
                        Fazer Download Agora
                    </a>
                </div>

                {{-- Card 3: Gestão de Usuários (Admin) --}}
                <div class="bg-white p-10 rounded-[3rem] border border-[#E1D3C1] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-[#8C846C]/10 rounded-2xl flex items-center justify-center text-xl mb-6">👥</div>
                        <h3 class="text-[11px] font-black uppercase tracking-widest text-gray-800 font-sans">Administradores</h3>
                        <p class="text-[10px] text-gray-400 mt-2 leading-relaxed italic">
                            Gerencie acessos administrativos (Secretaria/Equipe).
                        </p>
                    </div>
                    <a href="{{ route('settings.admins.index') }}" class="mt-8 w-full bg-[#8C846C] text-white py-3 rounded-xl font-black uppercase text-[9px] tracking-widest shadow-lg shadow-[#8C846C]/20 hover:-translate-y-0.5 transition-all text-center">
                        Gerenciar Equipe
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Script para o Modal de Senha Global --}}
    <script>
        function openGlobalPasswordModal() {
            Swal.fire({
                title: 'Alterar Senha Global',
                text: "Isso mudará a senha de TODOS os pacientes cadastrados no sistema.",
                input: 'password',
                inputPlaceholder: 'Digite a nova senha padrão',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirmar Alteração',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8C846C',
                cancelButtonColor: '#E1D3C1',
                customClass: {
                    popup: 'rounded-[2.5rem] border border-[#E1D3C1] font-sans',
                    title: 'font-serif italic text-gray-800',
                    confirmButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3'
                }
            }).then((result) => {
                // Dentro do seu .then((result) => { ... })
                if (result.isConfirmed && result.value) {
                    fetch("{{ route('settings.password.global') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ password: result.value })
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#8C846C',
                            customClass: { popup: 'rounded-[2.5rem] font-sans' }
                        });
                    })
                    .catch(error => {
                        Swal.fire('Erro!', 'Não foi possível atualizar as senhas.', 'error');
                    });
                }
            });
        }
    </script>
<script>
function openGlobalPasswordModal() {
    Swal.fire({
        title: 'Alterar Senha Global',
        html: `
            <div class="text-left px-1">
                <label class="text-[9px] uppercase font-black text-[#8C846C]/60 tracking-[0.2em] ml-1 mb-2 block">
                    Nova Senha Padrão
                </label>
                <div class="relative group">
                    <input type="password" id="global-password" 
                        class="w-full bg-[#F9F6F3] border border-[#E1D3C1] text-gray-800 text-sm rounded-2xl px-5 py-4 focus:ring-1 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all outline-none font-sans"
                        placeholder="Mínimo de 8 caracteres">
                    
                    <button type="button" onclick="togglePasswordVisibility()" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-[#8C846C]/40 hover:text-[#8C846C] transition-colors p-1"
                        id="password-toggle-btn">
                        {{-- Ícone Olho Aberto (SVG) --}}
                        <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{-- Ícone Olho Fechado (SVG) - Oculto inicialmente --}}
                        <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L4 4m5.352 5.352a3 3 0 004.293 4.293m0 0L20 20m-5.121-5.121L19.07 19.07M9.75 9.75L3 3" />
                        </svg>
                    </button>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirmar Alteração',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#8C846C',
        cancelButtonColor: '#E1D3C1',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-[3rem] border border-[#E1D3C1] p-8 shadow-2xl font-sans',
            title: 'text-2xl font-bold text-gray-800 tracking-tight mb-4 !p-0',
            confirmButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-8 py-4 shadow-lg shadow-[#8C846C]/20',
            cancelButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-8 py-4'
        },
        preConfirm: () => {
            const password = document.getElementById('global-password').value;
            if (!password || password.length < 8) {
                Swal.showValidationMessage('A senha deve conter ao menos 8 dígitos');
                return false;
            }
            return password;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Sua função de envio aqui
            enviarNovaSenha(result.value);
        }
    });
}

function togglePasswordVisibility() {
    const input = document.getElementById('global-password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    
    if (input.type === "password") {
        input.type = "text";
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = "password";
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
</x-app-layout>