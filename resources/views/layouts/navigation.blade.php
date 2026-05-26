<nav class="bg-[#F9F6F3] border-b border-[#E1D3C1] font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-24"> {{-- Altura levemente aumentada para acomodar a logo com respiro --}}
            
            {{-- Logo no Canto Esquerdo --}}
            {{-- Logo no Canto Esquerdo - Alinhamento Corrigido --}}
            <div class="flex-shrink-0 flex flex-col justify-center items-start">
                <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
                
                {{-- Ajustamos a margem negativa à esquerda para compensar a curva do "L" --}}
                <h1 class="text-4xl text-[#8C846C] leading-none -ml-1" style="font-family: 'Great Vibes', cursive;">
                    Lydia Sena
                </h1>
                
                {{-- Aumentamos o tracking para "esticar" a linha e alinhar com o nome acima --}}
                <p class="text-[7.5px] tracking-[0.42em] text-[#8C846C]/70 uppercase font-black mt-1 whitespace-nowrap">
                    Psicologia Clínica e Neuropsicologia
                </p>
            </div>

            {{-- Menu Centralizado --}}
            <div class="hidden md:flex items-center space-x-2">
                @if(Auth::user()->role === 'admin')
                    {{-- Início --}}
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-5 py-2 text-[10px] uppercase font-black tracking-[0.2em] transition-all duration-300 rounded-xl
                       {{ request()->routeIs('dashboard') ? 'bg-[#8C846C] text-white shadow-lg shadow-[#8C846C]/20' : 'text-[#8C846C]/60 hover:text-[#8C846C] hover:bg-white' }}">
                        <span class="mr-2 text-xs">🏠</span> Início
                    </a>

                    {{-- Pacientes --}}
                    <a href="{{ route('pacientes') }}" 
                       class="flex items-center px-5 py-2 text-[10px] uppercase font-black tracking-[0.2em] transition-all duration-300 rounded-xl
                       {{ request()->routeIs('pacientes*') ? 'bg-[#8C846C] text-white shadow-lg shadow-[#8C846C]/20' : 'text-[#8C846C]/60 hover:text-[#8C846C] hover:bg-white' }}">
                        <span class="mr-2 text-xs">👥</span> Pacientes
                    </a>

                    {{-- Relatórios --}}
                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center px-5 py-2 text-[10px] uppercase font-black tracking-[0.2em] transition-all duration-300 rounded-xl
                       {{ request()->routeIs('reports.index') ? 'bg-[#8C846C] text-white shadow-lg shadow-[#8C846C]/20' : 'text-[#8C846C]/60 hover:text-[#8C846C] hover:bg-white' }}">
                        <span class="mr-2 text-xs">📈</span> Relatórios
                    </a>

                    {{-- Configurações --}}
                    <a href="{{ route('settings.index') }}" 
                    class="flex items-center px-5 py-2 text-[10px] uppercase font-black tracking-[0.2em] transition-all duration-300 rounded-xl
                    {{ request()->routeIs('settings.index') 
                        ? 'bg-[#8C846C] text-white shadow-lg shadow-[#8C846C]/20' 
                        : 'text-[#8C846C]/60 hover:text-[#8C846C] hover:bg-white' }}">
                        <span class="mr-2 text-xs">⚙️</span> Configurações
                    </a>
                @else
                    <div class="flex items-center px-6 py-2.5 text-[10px] uppercase font-black tracking-[0.4em] text-[#8C846C]/40 italic">
                        Espaço do Paciente
                    </div>
                @endif
            </div>

            {{-- Cápsula de Identificação e Sair --}}
            <div class="flex-shrink-0 flex items-center bg-white/60 border border-[#E1D3C1] rounded-full pl-6 pr-2 py-1.5 shadow-sm">
                
                {{-- Identificação do Usuário --}}
                <div class="hidden lg:flex flex-col items-end mr-4">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[#8C846C]">
                        {{ Auth::user()->name }}
                    </span>
                    <span class="text-[7px] uppercase tracking-widest text-[#8C846C]/40 font-bold -mt-0.5">
                        {{ Auth::user()->role === 'admin' ? 'Acesso Administrativo' : 'Paciente' }}
                    </span>
                </div>

                {{-- Divisória Sutil dentro da cápsula --}}
                <div class="h-6 w-[1px] bg-[#E1D3C1] mr-2"></div>

                {{-- Botão Sair estilizado como botão interno --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="group flex items-center gap-2 px-4 py-2 text-[10px] uppercase font-black tracking-[0.2em] text-[#8C846C]/60 hover:text-red-700 transition-all duration-300 rounded-full hover:bg-white shadow-none hover:shadow-sm border border-transparent hover:border-[#E1D3C1]/30">
                        <span>Sair</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>