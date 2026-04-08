<nav class="bg-psico-pink border-b border-pink-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center h-16"> <div class="flex items-center space-x-4">
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-2 text-psico-text hover:bg-white/30 rounded-full transition {{ request()->routeIs('dashboard') ? 'bg-white/50 shadow-sm' : '' }}">
                   <span class="mr-2">🏠</span> Início
                </a>

                <a href="{{ route('pacientes') }}" 
                   class="flex items-center px-6 py-2 text-psico-text font-medium rounded-full transition {{ request()->routeIs('pacientes*') ? 'bg-[#E2B1B9] shadow-inner' : 'hover:bg-white/30' }}">
                   <span class="mr-2">👥</span> Pacientes
                </a>

               <a href="{{ route('reports.index') }}" 
                  class="flex items-center px-4 py-2 text-psico-text hover:bg-white/30 rounded-full transition {{ request()->routeIs('reports.index') ? 'bg-[#E2B1B9] shadow-inner font-bold' : '' }}">
                  <span class="mr-2">📈</span> Relatórios
               </a>

                <a href="#" 
                   class="flex items-center px-4 py-2 text-psico-text hover:bg-white/30 rounded-full transition">
                   <span class="mr-2">⚙️</span> Configurações
                </a>

            </div>
        </div>
    </div>
</nav>