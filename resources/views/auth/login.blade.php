<x-guest-layout>
    {{-- Injetamos estilos CSS nativos embutidos para garantir o layout correto caso o container pai tente espremer o conteúdo --}}
    <style>
        /* Desativa travas de tamanho que o Breeze coloca no container pai */
        .min-h-screen.flex.flex-col.justify-center.items-center {
            display: block !important;
            padding: 0 !important;
            max-w: 100% !important;
        }
        /* Ajuste fino para os inputs não perderem a identidade arredondada */
        .login-box input {
            border-radius: 1rem !important;
        }
    </style>

    {{-- Container Mestre: Ocupa 100% da largura e altura visíveis da tela --}}
    <div class="fixed inset-0 w-screen h-screen bg-[#F9F6F3] flex flex-col md:flex-row font-serif z-50 overflow-y-auto md:overflow-hidden">
        
        {{-- LADO ESQUERDO: Painel de Boas-Vindas Institucional --}}
        <div class="hidden md:flex md:w-1/2 h-full bg-[#F2ECE4] flex-col justify-between items-center p-16 border-r border-[#E1D3C1]/60 relative">
            {{-- Detalhes estéticos abstratos de fundo --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/30 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[#8C846C]/5 blur-3xl"></div>

            {{-- Elemento superior invisível apenas para empurrar o miolo para o centro perfeito com o space-between --}}
            <div></div>

            {{-- Conteúdo Central --}}
            <div class="max-w-sm text-center space-y-6 relative z-10">
                <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
                <h1 class="text-7xl text-[#8C846C]" style="font-family: 'Great Vibes', cursive;">Lydia Sena</h1>
                <div class="h-px w-20 bg-[#8C846C]/30 mx-auto"></div>
                <p class="text-[10px] tracking-[0.5em] text-[#8C846C] uppercase font-black">Psicologia Clínica e Neuropsicologia</p>
                <p class="text-xs font-serif italic text-[#8C846C]/60 leading-relaxed pt-4 max-w-xs mx-auto">
                    "Um espaço seguro focado no acolhimento, autoconhecimento e desenvolvimento clínico integrado."
                </p>
            </div>

            {{-- OPÇÃO 1 + 3: Assinatura e Créditos do Desenvolvedor Inteligente --}}
            <div class="relative z-10 font-sans opacity-40 hover:opacity-100 transition-all duration-300">
                <a href="https://instagram.com/escudero.dev" target="_blank" class="flex items-center gap-2.5 text-[#8C846C] group">
                    <span class="text-xs font-mono font-bold tracking-widest bg-[#8C846C]/10 text-[#8C846C] px-2 py-1 rounded-lg border border-[#8C846C]/20 group-hover:bg-[#8C846C] group-hover:text-white transition-colors duration-300">&lt;/&gt;</span>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] group-hover:underline decoration-1 underline-offset-4">Desenvolvido por @escudero.dev</span>
                </a>
            </div>
        </div>

        {{-- LADO DIREITO: Área Útil do Formulário de Login --}}
        <div class="w-full md:w-1/2 h-full flex flex-col justify-center items-center p-8 sm:p-12 md:p-24 bg-white login-box">
            
            {{-- Logo móvel (Celulares) --}}
            <div class="mb-8 text-center md:hidden opacity-90">
                <h1 class="text-5xl text-[#8C846C] mb-1" style="font-family: 'Great Vibes', cursive;">Lydia Sena</h1>
                <p class="text-[8px] tracking-[0.3em] text-[#8C846C] uppercase font-black">Psicologia Clínica</p>
            </div>

            <div class="w-full max-w-sm space-y-8">
                <div class="text-left">
                    <h2 class="text-3xl text-gray-800 italic font-serif">Bem-vindo(a) ao Espaço</h2>
                    <p class="text-[10px] uppercase font-black text-[#8C846C]/50 tracking-widest mt-1 font-sans">Identifique-se para acessar a plataforma</p>
                </div>

                {{-- ALERTA GLOBAL: Captura qualquer sinal de erro do Laravel --}}
                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-left font-sans transition-all">
                        <p class="text-[10px] font-black uppercase tracking-widest text-red-600 mb-1">⚠️ Falha na Autenticação</p>
                        <p class="text-xs text-red-500 font-medium italic">
                            Usuário, Matrícula de Acesso ou senha incorretos. Por favor, verifique os dados.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6 font-sans">
                    @csrf

                    {{-- Campo Identificador --}}
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Sua Matrícula de Acesso ou Usuário</label>
                        <input type="text" name="login_identifier" id="login_input" value="{{ old('login_identifier') }}" required autofocus
                            class="w-full border bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3.5 px-5 text-sm text-gray-700 outline-none placeholder:italic placeholder:text-[#8C846C]/30 {{ $errors->any() ? 'border-red-300 bg-red-50/10 focus:ring-red-400 focus:border-red-400' : 'border-[#E1D3C1]' }}"
                            placeholder="000.000.000-00 ou admin">
                    </div>

                    {{-- Campo Senha --}}
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Sua Senha</label>
                        <div class="relative group">
                            <input type="password" name="password" id="password_input" required autocomplete="current-password"
                                class="w-full border bg-[#F9F6F3]/50 focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] transition-all py-3.5 pl-5 pr-14 text-sm text-gray-700 outline-none {{ $errors->any() ? 'border-red-300 focus:ring-red-400 focus:border-red-400' : 'border-[#E1D3C1]' }}">
                            
                            {{-- Botão Espiar Senha --}}
                            <button type="button" onclick="togglePasswordVisibility()" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-1.5 rounded-xl text-[#8C846C]/40 hover:text-[#8C846C] hover:bg-[#8C846C]/5 transition-all outline-none" 
                                    title="Mostrar/Ocultar Senha">
                                <svg id="eye_icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Lembrar de mim --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C] h-4 w-4 transition-colors cursor-pointer">
                            <span class="ms-2 text-[10px] uppercase font-black text-[#8C846C]/40 tracking-widest group-hover:text-[#8C846C]">Lembrar de mim</span>
                        </label>
                    </div>

                    {{-- Botão de Submissão --}}
                    <button type="submit" class="w-full bg-[#8C846C] hover:bg-[#766f5a] text-white py-4 rounded-full font-black uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-[#8C846C]/10 transition-all transform hover:-translate-y-0.5 active:scale-95 outline-none">
                        Entrar no Sistema
                    </button>
                </form>

                <div class="text-center pt-4 border-t border-[#F9F6F3] md:hidden">
                    <p class="text-[9px] text-[#8C846C]/40 uppercase font-black tracking-widest">© {{ date('Y') }} Escudero Labs • Web apps • Gestão • Automação</p>
                </div>
            </div>

        </div>
    </div>

    {{-- Scripts Unificados --}}
    <script>
        const loginInput = document.getElementById('login_input');
        loginInput.addEventListener('input', function (e) {
            let value = e.target.value;
            if (/^\d/.test(value)) {
                value = value.replace(/\D/g, "");
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d)/, "$1.$2");
                    value = value.replace(/(\d{3})(\d)/, "$1.$2");
                    value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
                } else {
                    value = value.substring(0, 11);
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                }
                e.target.value = value;
            }
        });

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password_input');
            const eyeIcon = document.getElementById('eye_icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passwordInput.type === 'text';
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</x-guest-layout>