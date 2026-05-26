<x-app-layout>
    {{-- Checagem de segurança da sessão --}}
    @php
        $autorizado = session('clinical_access_authorized', false);
    @endphp

    <div class="py-12 bg-[#F9F6F3] min-h-screen text-left font-serif">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Cabeçalho --}}
            <div class="flex justify-between items-center mb-8 no-print">
                <div class="text-left">
                    <h2 class="text-3xl font-serif text-[#8C846C] italic">{{ $patient->name }}</h2>
                    <p class="text-[#8C846C]/60 font-bold uppercase text-[10px] tracking-widest">Prontuário Clínico Individualizado</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pacientes') }}" class="bg-white text-[#8C846C] border border-[#E1D3C1] px-6 py-2 rounded-full text-sm font-bold hover:bg-[#E1D3C1]/20 transition shadow-sm">Voltar</a>
                    <a href="{{ route('patients.edit', $patient->id) }}" class="bg-[#E1D3C1]/40 text-[#8C846C] px-6 py-2 rounded-full text-sm font-bold hover:bg-[#E1D3C1] transition flex items-center gap-2 border border-[#E1D3C1]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Editar Cadastro
                    </a>
                    <button onclick="document.getElementById('modalSessao').classList.remove('hidden')" class="bg-[#8C846C] text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:bg-[#766f5a] transition transform hover:scale-105">+ Registrar Sessão</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Coluna Esquerda: Informações e Documentos --}}
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-[#E1D3C1] h-fit">
                        <h3 class="text-lg font-serif italic text-[#8C846C] mb-6 border-b border-[#F9F6F3] pb-2">Informações Pessoais</h3>
                        <div class="space-y-5 text-sm">
                            <div><p class="text-[#8C846C]/40 uppercase text-[10px] font-black tracking-widest mb-1">WhatsApp</p><p class="text-[#8C846C] font-bold">{{ $patient->phone }}</p></div>
                            <div><p class="text-[#8C846C]/40 uppercase text-[10px] font-black tracking-widest mb-1">Matrícula de Acesso</p><p class="text-[#8C846C] font-bold">{{ $patient->cpf }}</p></div>
                            <div><p class="text-[#8C846C]/40 uppercase text-[10px] font-black tracking-widest mb-1">Cidade</p><p class="text-[#8C846C] font-bold">{{ $patient->city_state }}</p></div>
                            <div>
                                <p class="text-[#8C846C]/40 uppercase text-[10px] font-black tracking-widest mb-1">Data de Nascimento</p>
                                <p class="text-[#8C846C] font-bold">
                                    {{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') : 'Não informada' }}
                                </p>
                            </div>
                            <div><p class="text-[#8C846C]/40 uppercase text-[10px] font-black tracking-widest mb-1">Observações</p><p class="text-[#8C846C]/80 italic text-xs leading-relaxed bg-[#F9F6F3] p-3 rounded-2xl border border-[#E1D3C1]/30">{{ $patient->observations ?? 'Nenhuma observação.' }}</p></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 no-print">
                        {{-- Botão Principal de Anamnese --}}
                        <button onclick="{{ $autorizado ? 'abrirSelecaoAnamnese()' : 'solicitarPinClinico()' }}" class="group bg-white p-5 rounded-[2rem] border border-[#E1D3C1] shadow-sm hover:shadow-md hover:border-[#8C846C] transition-all text-left flex items-center justify-between w-full">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 {{ $autorizado ? 'bg-[#F9F6F3] text-[#8C846C]' : 'bg-red-50 text-red-500' }} rounded-2xl flex items-center justify-center group-hover:bg-[#8C846C] group-hover:text-white transition-colors">
                                    @if($autorizado)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-serif italic text-[#8C846C] font-bold">Anamnese</p>
                                    <p class="text-[9px] text-[#8C846C]/50 uppercase font-black tracking-widest">{{ $autorizado ? 'Modelos Disponíveis' : 'Bloqueado — Requer PIN' }}</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#E1D3C1] group-hover:text-[#8C846C] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>

                        <a href="{{ route('patients.plan', $patient->id) }}" class="group bg-white p-5 rounded-[2rem] border border-[#E1D3C1] shadow-sm hover:shadow-md hover:border-[#8C846C] transition-all text-left flex items-center justify-between w-full">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-[#F9F6F3] rounded-2xl flex items-center justify-center text-[#8C846C] group-hover:bg-[#8C846C] group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-serif italic text-[#8C846C] font-bold">Plano Terapêutico</p>
                                    <p class="text-[9px] text-[#8C846C]/50 uppercase font-black tracking-widest">Metas e Evolução Clínica</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#E1D3C1] group-hover:text-[#8C846C] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    {{-- Lista de Documentos Preenchidos (Protegido por CSS/Blur caso não autorizado) --}}
                    <div class="pt-6 space-y-3 no-print {{ !$autorizado ? 'pointer-events-none' : '' }}">
                        <h4 class="text-[10px] uppercase font-black text-[#8C846C]/40 tracking-widest ml-4 mb-2 font-sans">Documentos Preenchidos</h4>
                        
                        @php $hasDocument = false; @endphp

                        @if($patient->childNeuroAnamnesis)
                            @php $hasDocument = true; @endphp
                            <div class="group bg-white p-4 rounded-[1.5rem] border border-[#E1D3C1] flex items-center justify-between hover:border-[#8C846C] transition-all shadow-sm {{ !$autorizado ? 'blur-[3px] select-none opacity-40' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#F9F6F3] rounded-xl flex items-center justify-center text-[#8C846C]">🧠</div>
                                    <div class="text-left">
                                        <p class="text-[11px] font-bold text-[#8C846C] leading-tight">Anamnese Neuropsicológica Infantil</p>
                                        <p class="text-[9px] text-gray-400">Salvo em {{ $patient->childNeuroAnamnesis->updated_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('anamnese.neuro.infantil', $patient->id) }}" class="p-1.5 text-[#E1D3C1] hover:text-[#8C846C] transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></a>
                                    <form action="{{ route('anamnese.neuro.infantil.destroy', $patient->childNeuroAnamnesis->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmarExclusaoAnamnese(this)" class="p-1.5 text-red-200 hover:text-red-500 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if($patient->adultAnamnesis)
                            @php $hasDocument = true; @endphp
                            <div class="group bg-white p-4 rounded-[1.5rem] border border-[#E1D3C1] flex items-center justify-between hover:border-[#8C846C] transition-all shadow-sm {{ !$autorizado ? 'blur-[3px] select-none opacity-40' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#F9F6F3] rounded-xl flex items-center justify-center text-[#8C846C]">👤</div>
                                    <div class="text-left">
                                        <p class="text-[11px] font-bold text-[#8C846C] leading-tight">Anamnese Psicológica Adulto</p>
                                        <p class="text-[9px] text-gray-400">Salvo em {{ $patient->adultAnamnesis->updated_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('anamnese.psico.adulto', $patient->id) }}" class="p-1.5 text-[#E1D3C1] hover:text-[#8C846C] transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></a>
                                    <form action="{{ route('anamnese.psico.adulto.destroy', $patient->adultAnamnesis->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmarExclusaoAnamnese(this)" class="p-1.5 text-red-200 hover:text-red-500 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if($patient->adultNeuroAnamnesis)
                            @php $hasDocument = true; @endphp
                            <div class="group bg-white p-4 rounded-[1.5rem] border border-[#E1D3C1] flex items-center justify-between hover:border-[#8C846C] transition-all shadow-sm {{ !$autorizado ? 'blur-[3px] select-none opacity-40' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#F9F6F3] rounded-xl flex items-center justify-center text-[#8C846C]">🔬</div>
                                    <div class="text-left">
                                        <p class="text-[11px] font-bold text-[#8C846C] leading-tight">Anamnese Neuropsicológica Adulto</p>
                                        <p class="text-[9px] text-gray-400">Salvo em {{ $patient->adultNeuroAnamnesis->updated_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('anamnese.neuro.adulto', $patient->id) }}" class="p-1.5 text-[#E1D3C1] hover:text-[#8C846C] transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></a>
                                    <form action="{{ route('anamnese.neuro.adulto.destroy', $patient->adultNeuroAnamnesis->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmarExclusaoAnamnese(this)" class="p-1.5 text-red-200 hover:text-red-500 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if($patient->childPsicoAnamnesis)
                            @php $hasDocument = true; @endphp
                            <div class="group bg-white p-4 rounded-[1.5rem] border border-[#E1D3C1] flex items-center justify-between hover:border-[#8C846C] transition-all shadow-sm {{ !$autorizado ? 'blur-[3px] select-none opacity-40' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#F9F6F3] rounded-xl flex items-center justify-center text-[#8C846C]">🧸</div>
                                    <div class="text-left">
                                        <p class="text-[11px] font-bold text-[#8C846C] leading-tight">Anamnese Psicológica Infantil</p>
                                        <p class="text-[9px] text-gray-400">Salvo em {{ $patient->childPsicoAnamnesis->updated_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('anamnese.psico.infantil', $patient->id) }}" class="p-1.5 text-[#E1D3C1] hover:text-[#8C846C] transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></a>
                                    <form action="{{ route('anamnese.psico.infantil.destroy', $patient->childPsicoAnamnesis->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmarExclusaoAnamnese(this)" class="p-1.5 text-red-200 hover:text-red-500 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if(!$hasDocument)
                            <div class="px-4 py-3 border border-dashed border-[#E1D3C1] rounded-2xl text-center">
                                <p class="text-[10px] text-gray-400 italic">Nenhum documento preenchido.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Coluna Direita: Histórico e Notas Clínicas --}}
                <div class="md:col-span-2 space-y-8">
                    {{-- HISTÓRICO DE ATENDIMENTOS --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center border-b border-[#F9F6F3] pb-4">
                            <h3 class="text-xl font-serif italic text-[#8C846C]">Histórico de Atendimentos</h3>
                            <div class="flex items-center gap-3 bg-[#F9F6F3] px-3 py-1.5 rounded-full border border-[#E1D3C1]/50">
                                <a href="{{ route('patients.show', ['patient' => $patient->id, 'month' => $month, 'year' => $year - 1]) }}" class="p-1 text-[#8C846C] hover:scale-110 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
                                </a>
                                <span class="text-[10px] font-black text-[#8C846C] uppercase tracking-[0.2em] w-16 text-center">{{ $year }}</span>
                                <a href="{{ route('patients.show', ['patient' => $patient->id, 'month' => $month, 'year' => $year + 1]) }}" class="p-1 text-[#8C846C] hover:scale-110 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>

                        <div class="mt-4 mb-8 flex flex-wrap items-center justify-start gap-1.5">
                            @foreach(range(1, 12) as $m)
                                @php $isActive = ($month == $m); @endphp
                                <a href="{{ route('patients.show', ['patient' => $patient->id, 'month' => $m, 'year' => $year]) }}" 
                                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all duration-300 {{ $isActive ? 'bg-[#8C846C] text-white shadow-lg scale-105' : 'bg-white text-[#8C846C]/50 hover:bg-[#F9F6F3] border border-[#E1D3C1]/30' }}">
                                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('M') }}
                                </a>
                            @endforeach
                        </div>

                        @if($sessions->isEmpty())
                            <div class="text-center py-12 bg-[#F9F6F3]/50 rounded-[2.5rem] border border-dashed border-[#E1D3C1]">
                                <p class="text-[#8C846C]/40 italic text-sm">Nenhuma sessão encontrada para este mês.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($sessions as $session)
                                    <div class="group flex items-center justify-between p-5 historico-card border border-[#E1D3C1]/50 rounded-[2rem] transition hover:shadow-xl hover:-translate-y-1 duration-300 relative overflow-hidden">
                                        @if($session->status == 'pago')
                                            <div class="indicador-pago"></div>
                                        @endif

                                        <div class="flex items-center gap-5 text-left flex-1 overflow-hidden">
                                            <div class="bg-[#F9F6F3] p-3 rounded-2xl text-center min-w-[65px] shadow-sm border border-[#E1D3C1]/30 group-hover:bg-[#8C846C] group-hover:text-white transition-colors duration-500">
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
                                            <div class="flex flex-col items-center">
                                                <form action="{{ route('sessions.markPerformed', $session->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="mb-1 p-2 rounded-xl transition-all {{ $session->performed ? 'text-green-600 bg-green-50' : 'text-[#8C846C]/30 bg-[#F9F6F3]' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    </button>
                                                </form>
                                                <span class="text-[7px] font-black uppercase tracking-tighter {{ $session->performed ? 'text-green-600' : 'text-[#8C846C]/40' }}">
                                                    {{ $session->performed ? 'Consulta realizada' : ' Consulta Agendada' }}
                                                </span>
                                            </div>

                                            <div class="text-right border-l border-[#E1D3C1]/30 pl-6">
                                                <p class="text-sm font-black text-[#8C846C] mb-1">R$ {{ number_format($session->value, 2, ',', '.') }}</p>
                                                <form action="{{ route('sessions.updateStatus', $session->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-[8px] px-3 py-1 rounded-full font-black uppercase tracking-tighter transition-all {{ $session->status == 'pago' ? 'bg-green-600 text-white shadow-sm' : 'bg-white text-[#8C846C] border border-[#E1D3C1]' }}">
                                                        {{ $session->status }}
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="flex items-center border-l border-[#E1D3C1]/30 pl-4 gap-1">
                                                {{-- FORMULÁRIO: Exclusão Simples --}}
                                                <form id="delete-session-{{ $session->id }}" action="{{ route('sessions.destroy', $session->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    {{-- Passamos o ID e se está Pago (1 ou 0) --}}
                                                    <button type="button" onclick="confirmarExclusaoSimples({{ $session->id }}, {{ $session->status == 'pago' ? 1 : 0 }})" class="p-2 text-[#E1D3C1] hover:text-red-500 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>

                                                {{-- FORMULÁRIO: Exclusão Recorrente (Série Futura) --}}
                                                @if($session->is_recurrent)
                                                    <form id="delete-recursive-{{ $session->id }}" action="{{ route('sessions.destroyRecursive', $session->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        {{-- Passamos o ID e se está Pago (1 ou 0) --}}
                                                        <button type="button" onclick="confirmarExclusaoRecorrente({{ $session->id }}, {{ $session->status == 'pago' ? 1 : 0 }})" class="p-2 text-[#8C846C]/30 hover:text-orange-500 transition-colors">
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
                    </div>

                    {{-- CARD: EVOLUÇÃO CLÍNICA (Totalmente Blindado por CSS/Blur caso não autorizado) --}}
                    <div class="bg-white rounded-[3rem] p-8 border border-[#E1D3C1] shadow-sm relative">
                        <div class="flex justify-between items-center mb-8 border-b border-[#F9F6F3] pb-4">
                            <div class="text-left">
                                <h3 class="text-2xl font-serif text-[#8C846C] italic">Evolução Clínica</h3>
                                <p class="text-[10px] uppercase font-black text-[#8C846C]/40 tracking-widest">Registros Terapêuticos</p>
                            </div>
                            <button onclick="{{ $autorizado ? 'abrirNovoRegistro()' : 'solicitarPinClinico()' }}" class="bg-[#E1D3C1]/40 text-[#8C846C] px-5 py-2.5 rounded-2xl font-bold text-xs shadow-sm hover:bg-[#E1D3C1] transition border border-[#E1D3C1]">
                                {{ $autorizado ? 'Adicionar Registro' : '🔑 Desbloquear Painel' }}
                            </button>
                        </div>
                        
                        {{-- Cortina de Proteção Absoluta --}}
                        @if(!$autorizado)
                            <div class="absolute inset-x-0 bottom-0 top-24 bg-white/10 backdrop-blur-[6px] rounded-b-[3rem] z-40 flex flex-col items-center justify-center p-8 text-center">
                                <div class="bg-amber-50 text-amber-700 p-4 rounded-2xl border border-amber-200/60 max-w-sm shadow-sm font-sans">
                                    <p class="text-xs font-black uppercase tracking-wider mb-1">🛡️ Área Clínica Restrita</p>
                                    <p class="text-[11px] text-amber-700/80 leading-relaxed font-medium">Os relatos e laudos de evolução contêm dados sob sigilo médico. Forneça o PIN secundário para leitura.</p>
                                    <button onclick="solicitarPinClinico()" class="mt-3 bg-amber-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow hover:bg-amber-800 transition">Inserir Senha Clínica</button>
                                </div>
                            </div>
                        @endif

                        <div class="relative pl-10 before:absolute before:inset-y-0 before:left-[19px] before:w-0.5 before:bg-[#E1D3C1] {{ !$autorizado ? 'select-none opacity-20' : '' }}">
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
                                                        <a href="{{ asset('storage/' . $note->attachment) }}" target="_blank" class="p-2 bg-white text-[#7C9A92] hover:text-[#5a706a] rounded-lg border border-[#E1D3C1] shadow-sm transition-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
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
            </div>
        </div>
    </div>

    {{-- MODAIS DO SISTEMA: Registrar Nova Sessão --}}
    <div id="modalSessao" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[120] px-4">
        <div class="bg-white rounded-[2.5rem] p-10 max-w-md w-full shadow-2xl border border-[#E1D3C1] text-left">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-serif italic text-[#8C846C]">Nova Sessão</h3>
                <button onclick="document.getElementById('modalSessao').classList.add('hidden')" class="text-[#E1D3C1] hover:text-[#8C846C] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="{{ route('sessions.store', $patient->id) }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Seleção do Dia da Semana --}}
                <div>
                    <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-3 tracking-[0.15em]">Dia do Atendimento</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Seg' => 'Monday', 'Ter' => 'Tuesday', 'Qua' => 'Wednesday', 'Qui' => 'Thursday', 'Sex' => 'Friday', 'Sáb' => 'Saturday', 'Dom' => 'Sunday'] as $label => $value)
                            <label class="cursor-pointer">
                                <input type="radio" name="day_of_week" id="day_{{ $value }}" value="{{ $value }}" class="hidden peer" required>
                                <div class="px-3.5 py-2 border border-[#E1D3C1] rounded-xl text-[10px] font-bold text-[#8C846C]/40 peer-checked:bg-[#8C846C] peer-checked:text-white transition shadow-sm">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Data e Hora --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2">Data</label>
                        <input type="date" name="session_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3]/50 text-sm focus:ring-[#8C846C]">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2">Hora</label>
                        <input type="time" name="session_time" required class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3]/50 text-sm focus:ring-[#8C846C]">
                    </div>
                </div>

                {{-- Serviço e Valor --}}
                <div>
                    <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2">Serviço e Valor</label>
                    <select name="service_type" class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3]/50 text-sm focus:ring-[#8C846C] mb-3">
                        <option value="1">Psicoterapia</option>
                        <option value="2">Avaliação</option>
                        <option value="3">Consultoria</option>
                    </select>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[#8C846C]">R$</span>
                        <input type="number" name="value" value="120.00" step="0.01" class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3] pl-10 font-black text-[#8C846C]">
                    </div>
                </div>

                {{-- Bloco de Recorrência Inteligente Otimizado --}}
                <div class="space-y-4 p-5 bg-[#F9F6F3] rounded-[2rem] border border-[#E1D3C1]/50 text-left">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_recurrent" id="is_recurrent_prontuario" onchange="toggleFrequenciaProntuario(this.checked)" class="rounded text-[#8C846C] focus:ring-[#8C846C] h-5 w-5 cursor-pointer">
                        <label for="is_recurrent_prontuario" class="text-[11px] font-bold text-[#8C846C] italic cursor-pointer select-none">
                            Este agendamento se repete?
                        </label>
                    </div>

                    {{-- Começa invisível e se expande se o checkbox for ativo --}}
                    <div id="container_frequencia_prontuario" class="hidden transition-all duration-300 pt-2 border-t border-[#E1D3C1]/30">
                        <label class="block text-[9px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Frequência da Repetição</label>
                        <select name="recurrence_period" class="w-full rounded-xl border-[#E1D3C1] bg-white text-xs font-bold text-gray-700 focus:ring-[#8C846C] focus:border-[#8C846C]">
                            <option value="weekly">Semanalmente (Toda semana)</option>
                            <option value="biweekly">Quinzenalmente (De 15 em 15 dias)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#8C846C] text-white py-4 rounded-2xl font-bold uppercase text-xs tracking-widest shadow-lg hover:bg-[#766f5a] transition">Salvar Sessão</button>
            </form>
        </div>
    </div>

    <div id="modalProntuario" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[110] px-4 no-print">
        <div class="bg-white rounded-[2.5rem] p-10 max-w-2xl w-full shadow-2xl border border-[#E1D3C1] relative text-left">
            <button onclick="document.getElementById('modalProntuario').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-[#8C846C]">✕</button>
            <h3 class="text-2xl font-serif italic text-[#8C846C] mb-6">Registro de Evolução Clínica</h3>
            <form action="{{ route('notes.store', $patient->id) }}" method="POST" enctype="multipart/form-data" id="formEvolucao">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-1">Data do Atendimento</label>
                        <input type="date" name="session_date" id="nota_data" required class="w-full rounded-xl border-[#E1D3C1] text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-1">Anexo / Documento</label>
                        <input type="file" name="attachment" id="nota_anexo" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:bg-[#E1D3C1]/20 file:text-[#8C846C]">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-1">Relato da Sessão</label>
                    <textarea name="content" id="nota_conteudo" placeholder="Descreva a evolução do paciente..." required class="w-full rounded-xl border-[#E1D3C1] text-sm" rows="8"></textarea>
                </div>
                <button type="submit" class="w-full bg-[#8C846C] text-white py-4 rounded-xl font-bold uppercase text-xs tracking-[0.2em] hover:bg-[#766f5a] transition shadow-md">Salvar Registro no Prontuário</button>
            </form>
        </div>
    </div>

    <div id="modalAnamnese" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[120] px-4 no-print">
        <div class="bg-white rounded-[3rem] p-10 max-w-2xl w-full shadow-2xl border border-[#E1D3C1] text-center">
            <h3 class="text-2xl font-serif italic text-[#8C846C] mb-2">Modelos de Anamnese</h3>
            <p class="text-[10px] uppercase font-black text-[#8C846C]/40 tracking-[0.2em] mb-8">Escolha o documento para preencher</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                <a href="{{ route('anamnese.neuro.infantil', $patient->id) }}" class="group p-6 bg-[#F9F6F3] border border-[#E1D3C1]/50 rounded-[2rem] hover:border-[#8C846C] hover:bg-white transition-all flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl">🧠</div>
                    <div><p class="text-xs font-bold text-[#8C846C]">Neuropsicológica Infantil</p></div>
                </a>
                <a href="{{ route('anamnese.psico.adulto', $patient->id) }}" class="group p-6 bg-[#F9F6F3] border border-[#E1D3C1]/50 rounded-[2rem] hover:border-[#8C846C] hover:bg-white transition-all flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl">👤</div>
                    <div><p class="text-xs font-bold text-[#8C846C]">Psicológica Adulto</p></div>
                </a>
                <a href="{{ route('anamnese.neuro.adulto', $patient->id) }}" class="group p-6 bg-[#F9F6F3] border border-[#E1D3C1]/50 rounded-[2rem] hover:border-[#8C846C] hover:bg-white transition-all flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl">🔬</div>
                    <div><p class="text-xs font-bold text-[#8C846C]">Neuropsicológica Adulto</p></div>
                </a>
                <a href="{{ route('anamnese.psico.infantil', $patient->id) }}" class="group p-6 bg-[#F9F6F3] border border-[#E1D3C1]/50 rounded-[2rem] hover:border-[#8C846C] hover:bg-white transition-all flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl">🧸</div>
                    <div><p class="text-xs font-bold text-[#8C846C]">Psicológica Infantil</p></div>
                </a>
            </div>
            <button onclick="document.getElementById('modalAnamnese').classList.add('hidden')" class="mt-8 text-xs font-bold text-[#8C846C]/40 uppercase tracking-tighter hover:text-[#8C846C]">Fechar Menu</button>
        </div>
    </div>

    {{-- SCRIPTS DE SEGURANÇA E GERENCIAMENTO --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const swalLydia = { confirmButtonColor: '#8C846C', cancelButtonColor: '#E1D3C1', customClass: { popup: 'rounded-[2rem]', title: 'font-serif italic text-[#8C846C]' } };

        // MODAL DE INTERCEPÇÃO DO PIN CLÍNICO
        function solicitarPinClinico() {
            Swal.fire({
                title: 'PIN de Segurança Clínica',
                text: 'Insira sua senha de descriptografia clínica para visualizar relatos e anamneses.',
                input: 'password',
                inputPlaceholder: '••••',
                showCancelButton: true,
                confirmButtonText: 'Desbloquear',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8C846C',
                cancelButtonColor: '#E1D3C1',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] border border-[#E1D3C1] p-8 font-sans',
                    title: 'font-serif italic text-gray-800 text-2xl'
                },
                preConfirm: (pin) => {
                    if (!pin) {
                        Swal.showValidationMessage('O PIN de acesso é obrigatório');
                        return false;
                    }
                    return fetch('/verificar-pin-clinico', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ pin: pin })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(error.message);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Recarrega a página com o estado da sessão atualizado para "autorizado"
                    window.location.reload();
                }
            });
        }

        function toggleFrequenciaProntuario(isCheck) {
                const container = document.getElementById('container_frequencia_prontuario');
                if (isCheck) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }

        function abrirSelecaoAnamnese() { document.getElementById('modalAnamnese').classList.remove('hidden'); }
        function abrirNovoRegistro() {
            const modal = document.getElementById('modalProntuario');
            const form = document.getElementById('formEvolucao');
            form.reset();
            form.action = "{{ route('notes.store', $patient->id) }}";
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            modal.classList.remove('hidden');
        }

        function abrirEdicaoNota(note) {
            const modal = document.getElementById('modalProntuario');
            const form = document.getElementById('formEvolucao');
            modal.querySelector('h3').innerText = "Editar Registro Clínico";
            form.action = `/notes/${note.id}`;
            if (!form.querySelector('input[name="_method"]')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.value = 'PUT';
                form.appendChild(input);
            }
            document.getElementById('nota_data').value = note.session_date.split('T')[0];
            document.getElementById('nota_conteudo').value = note.content;
            modal.classList.remove('hidden');
        }

        function confirmarExclusaoNota(id) {
            Swal.fire({ title: 'Excluir registro?', text: "Esta ação não pode ser desfeita.", icon: 'warning', showCancelButton: true, confirmButtonText: 'Sim, excluir', ...swalLydia }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-note-' + id).submit();
            });
        }

        function confirmarExclusaoAnamnese(button) {
            Swal.fire({ title: 'Excluir Anamnese?', text: "Isso removerá todo o histórico deste documento.", icon: 'warning', showCancelButton: true, confirmButtonText: 'Sim, excluir', ...swalLydia }).then((result) => {
                if (result.isConfirmed) button.closest('form').submit();
            });
        }

        function confirmarExclusaoSimples(id, estaPago) {
            // Se estiver pago, exibe o Toast e bloqueia
            if (estaPago == 1 || estaPago == '1') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'warning',
                    title: 'Esta consulta já está paga e não pode ser excluída.'
                });
                return; // Trava o fluxo
            }

            // Se não estiver pago, abre o fluxo de confirmação original
            Swal.fire({
                title: 'Excluir esta sessão?', 
                text: "Apenas este atendimento será removido.", 
                icon: 'question', 
                showCancelButton: true, 
                confirmButtonColor: '#ef4444', 
                cancelButtonColor: '#E1D3C1', 
                confirmButtonText: 'Sim, excluir', 
                cancelButtonText: 'Manter', 
                customClass: { popup: 'rounded-[2.5rem] font-sans', title: 'font-serif italic' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-session-' + id).submit();
            });
        }

        function confirmarExclusaoRecorrente(id, estaPago) {
            // Se estiver pago, exibe o Toast e bloqueia
            if (estaPago == 1 || estaPago == '1') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'warning',
                    title: 'Esta consulta já está paga e não pode ser excluída.'
                });
                return; // Trava o fluxo
            }

            // Se não estiver pago, abre o fluxo de confirmação original
            Swal.fire({
                title: 'Excluir recorrência?', 
                text: "Isso apagará todas as sessões FUTURAS baseadas neste horário.", 
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#8C846C', 
                cancelButtonColor: '#E1D3C1', 
                confirmButtonText: 'Sim, apagar futuras', 
                customClass: { popup: 'rounded-[2.5rem] font-sans', title: 'font-serif italic' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-recursive-' + id).submit();
            });
        }
    </script>

    {{-- ESTILOS CSS REFINADOS --}}
    <style>
        nav[role="navigation"] { display: flex !important; justify-content: center !important; margin-top: 3rem !important; width: 100% !important; }
        nav[role="navigation"] p, nav[role="navigation"] svg, nav[role="navigation"] a[rel="prev"], nav[role="navigation"] a[rel="next"], nav[role="navigation"] span[aria-disabled="true"] { display: none !important; }
        nav[role="navigation"] div.hidden, nav[role="navigation"] .sm\:flex-1 { display: flex !important; flex-direction: row !important; justify-content: center !important; align-items: center !important; }
        nav[role="navigation"] a, nav[role="navigation"] span.relative.inline-flex { display: inline-flex !important; align-items: center !important; justify-content: center !important; width: 40px !important; height: 40px !important; margin: 0 4px !important; border-radius: 12px !important; border: 1px solid #E1D3C1 !important; background-color: white !important; color: #8C846C !important; font-weight: 800 !important; font-size: 13px !important; text-decoration: none !important; }
        nav[role="navigation"] span[aria-current="page"] span { background-color: #8C846C !important; color: white !important; border-color: #8C846C !important; width: 100% !important; height: 100% !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 11px !important; }
        nav[role="navigation"] a:hover { background-color: #F9F6F3 !important; border-color: #8C846C !important; transform: translateY(-2px) !important; }
        .historico-card { background-color: white !important; }
        .indicador-pago { position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background-color: #22c55e; opacity: 0.5; z-index: 20; }
    </style>
</x-app-layout>