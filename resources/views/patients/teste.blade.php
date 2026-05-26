<div class="md:col-span-2 space-y-8">
    {{-- CARD: HISTÓRICO DE ATENDIMENTOS --}}
    <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-[#E1D3C1]">
        <div class="flex justify-between items-center mb-6 border-b border-[#F9F6F3] pb-4">
            <h3 class="text-xl font-serif italic text-[#8C846C]">Histórico de Atendimentos</h3>
            <span class="text-[10px] font-black text-[#8C846C]/40 uppercase tracking-[0.2em]">
                {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
            </span>
        </div>

        @if($sessions->isEmpty())
            <div class="text-center py-12 bg-[#F9F6F3]/50 rounded-[2.5rem] border border-dashed border-[#E1D3C1]">
                <p class="text-[#8C846C]/40 italic text-sm">Nenhuma sessão encontrada para este mês.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($sessions as $session)
                    <div class="group flex items-center justify-between p-5 {{ $session->status == 'pago' ? 'bg-green-50/30 border-green-100' : 'bg-[#F9F6F3] border-[#E1D3C1]/50' }} rounded-[2rem] border transition hover:bg-white hover:shadow-xl hover:-translate-y-1 duration-300">
                        
                        <div class="flex items-center gap-5 text-left flex-1 overflow-hidden">
                            <div class="bg-white p-3 rounded-2xl text-center min-w-[65px] shadow-sm border border-[#E1D3C1]/30 group-hover:bg-[#8C846C] group-hover:text-white transition-colors duration-500">
                                <p class="text-[10px] font-black uppercase leading-tight">{{ $session->session_date->translatedFormat('M') }}</p>
                                <p class="text-xl font-serif font-bold leading-none">{{ $session->session_date->format('d') }}</p>
                            </div>

                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                    {{ $session->service_type == 1 ? 'Psicoterapia' : ($session->service_type == 2 ? 'Avaliação' : 'Consultoria') }}
                                    @if($session->is_recurrent)
                                        <span class="bg-blue-50 text-blue-500 text-[8px] px-2 py-0.5 rounded-full border border-blue-100 uppercase font-black">Recorrente</span>
                                    @endif
                                    <span class="text-[10px] font-normal text-[#8C846C]/40">({{ $session->session_date->format('H:i') }})</span>
                                </p>
                                <p class="text-xs text-[#8C846C]/70 italic truncate">{{ $session->notes ?? 'Sem anotações' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <div class="text-right">
                                <p class="text-sm font-black text-[#8C846C]">R$ {{ number_format($session->value, 2, ',', '.') }}</p>
                                <form action="{{ route('sessions.updateStatus', $session->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-[8px] px-2 py-0.5 rounded-full font-black uppercase tracking-tighter transition-all {{ $session->status == 'pago' ? 'bg-green-500 text-white shadow-sm' : 'bg-white text-[#8C846C] border border-[#E1D3C1]' }}">
                                        {{ $session->status }}
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center border-l border-[#E1D3C1]/30 pl-4 gap-1">
                                {{-- BOTÃO EXCLUIR SIMPLES --}}
                                <form id="delete-session-{{ $session->id }}" action="{{ route('sessions.destroy', $session->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmarExclusaoSimples({{ $session->id }})" class="p-2 text-[#E1D3C1] hover:text-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>

                                {{-- BOTÃO EXCLUIR RECORRÊNCIA --}}
                                @if($session->is_recurrent && $session->status == 'pendente')
                                    <form id="delete-recursive-{{ $session->id }}" action="{{ route('sessions.destroyRecursive', $session->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmarExclusaoRecorrente({{ $session->id }})" class="p-2 text-blue-200 hover:text-blue-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Seletor de Meses --}}
        <div class="mt-8 flex flex-wrap items-center justify-center gap-1.5 border-t border-[#F9F6F3] pt-6">
            @foreach(range(1, 12) as $m)
                @php $isActive = ($month == $m); @endphp
                <a href="{{ route('patients.show', ['patient' => $patient->id, 'month' => $m, 'year' => $year]) }}" 
                   class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all duration-300 {{ $isActive ? 'bg-[#8C846C] text-white shadow-lg scale-105' : 'bg-white text-[#8C846C]/50 hover:bg-[#F9F6F3] border border-[#E1D3C1]/30' }}">
                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('M') }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- CARD: EVOLUÇÃO CLÍNICA --}}
    <div class="bg-white rounded-[3rem] p-8 border border-[#E1D3C1] shadow-sm">
                        <div class="flex justify-between items-center mb-8 border-b border-[#F9F6F3] pb-4">
                            <div class="text-left">
                                <h3 class="text-2xl font-serif text-[#8C846C] italic">Evolução Clínica</h3>
                                <p class="text-[10px] uppercase font-black text-[#8C846C]/40 tracking-widest">Registros Terapêuticos</p>
                            </div>
                            <button onclick="abrirNovoRegistro()" class="bg-[#E1D3C1]/40 text-[#8C846C] px-5 py-2.5 rounded-2xl font-bold text-xs shadow-sm hover:bg-[#E1D3C1] transition border border-[#E1D3C1]">Adicionar Registro</button>
                        </div>
                        
                        <div class="relative pl-10 before:absolute before:inset-y-0 before:left-[19px] before:w-0.5 before:bg-[#E1D3C1]">
                            <div class="space-y-10">
                                @forelse($progressNotes as $note)
                                    <div class="relative text-left">
                                        <div class="absolute -left-10 top-2 w-10 h-10 bg-[#F9F6F3] rounded-full border-4 border-white flex items-center justify-center shadow-sm z-10">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#8C846C]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="bg-[#F9F6F3]/50 p-6 rounded-[2rem] border border-[#E1D3C1]/30 shadow-sm transition-all hover:bg-white hover:shadow-md group">
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <span class="text-[10px] font-black text-[#8C846C] uppercase bg-[#E1D3C1]/40 px-3 py-1 rounded-full">
                                                        Sessão: {{ $note->session_date->format('d/m/Y') }}
                                                    </span>
                                                    
                                                    @if($note->attachment)
                                                        <a href="{{ asset('storage/' . $note->attachment) }}" target="_blank" 
                                                        class="p-2 bg-white text-[#7C9A92] hover:text-[#5a706a] rounded-lg border border-[#E1D3C1] shadow-sm transition-all" 
                                                        title="Ver Documento Anexo">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button onclick="abrirEdicaoNota({{ json_encode($note) }})" class="p-1 text-[#E1D3C1] hover:text-[#8C846C] transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                                    <form id="delete-note-{{ $note->id }}" action="{{ route('notes.destroy', $note->id) }}" method="POST">@csrf @method('DELETE')
                                                        <button type="button" onclick="confirmarExclusaoNota({{ $note->id }})" class="p-1 text-[#E1D3C1] hover:text-red-400 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="text-[#8C846C] text-sm leading-relaxed whitespace-pre-line font-medium">{{ $note->content }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-10 bg-[#F9F6F3] rounded-[2rem] border border-dashed border-[#E1D3C1]"><p class="text-[#8C846C]/30 italic text-sm">Nenhum registro clínico.</p></div>
                                @endforelse
                            </div>
                        </div>
                        <div class="mt-10 pt-6 border-t border-[#F9F6F3]">{{ $progressNotes->appends(request()->query())->links() }}</div>
                    </div>
</div>