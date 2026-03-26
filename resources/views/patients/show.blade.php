<x-app-layout>
    <div class="py-12 bg-[#FDF2F4] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-serif text-gray-800">{{ $patient->name }}</h2>
                    <p class="text-pink-500 font-medium">Prontuário Clínico</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pacientes') }}" class="bg-white text-gray-500 border border-gray-200 px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-50 transition">
                        Voltar
                    </a>

                    <a href="{{ route('patients.edit', $patient->id) }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-200 transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Editar Cadastro
                    </a>

                    <button onclick="document.getElementById('modalSessao').classList.remove('hidden')" 
                            class="bg-pink-500 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:bg-pink-600 transition">
                        + Registrar Sessão
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100 h-fit">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informações Pessoais</h3>
                    <div class="space-y-4 text-sm">
                        <div><p class="text-gray-400 uppercase text-[10px] font-bold">WhatsApp</p><p class="text-gray-700">{{ $patient->phone }}</p></div>
                        <div><p class="text-gray-400 uppercase text-[10px] font-bold">CPF</p><p class="text-gray-700">{{ $patient->cpf }}</p></div>
                        <div><p class="text-gray-400 uppercase text-[10px] font-bold">Cidade</p><p class="text-gray-700">{{ $patient->city_state }}</p></div>
                        <div><p class="text-gray-400 uppercase text-[10px] font-bold">Observações</p><p class="text-gray-700 italic text-xs">{{ $patient->observations ?? 'Nenhuma observação cadastrada.' }}</p></div>
                    </div>
                </div>

                <div class="md:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-pink-100">
                    <div class="flex justify-between items-center mb-6 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-800">Histórico de Atendimentos</h3>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                            {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                        </span>
                    </div>
                    
                    @if($sessions->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-400 italic">Nenhuma sessão encontrada para este mês.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($sessions as $session)
                                <div class="flex items-center justify-between p-4 bg-pink-50/30 rounded-2xl border border-pink-100 transition hover:bg-white hover:shadow-md">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="bg-white p-2 rounded-xl text-center min-w-[55px] shadow-sm border border-pink-50">
                                            <p class="text-[9px] font-bold text-pink-500 uppercase">{{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('M') }}</p>
                                            <p class="text-lg font-bold text-gray-700 leading-none">{{ \Carbon\Carbon::parse($session->session_date)->format('d') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                                @if($session->service_type == 1) Psicoterapia
                                                @elseif($session->service_type == 2) Avaliação
                                                @elseif($session->service_type == 3) Consultoria
                                                @endif
                                                
                                                @if($session->is_recurrent)
                                                    <span class="bg-blue-50 text-blue-500 text-[8px] px-1.5 py-0.5 rounded border border-blue-100 uppercase tracking-tighter font-black">Recorrente</span>
                                                @endif
                                                <span class="text-[10px] font-normal text-gray-400">({{ \Carbon\Carbon::parse($session->session_date)->format('H:i') }})</span>
                                            </p>
                                            <p class="text-xs text-gray-400 italic truncate">{{ $session->notes ?? 'Sem anotações' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-pink-600">R$ {{ number_format($session->value, 2, ',', '.') }}</p>
                                            <form action="{{ route('sessions.updateStatus', $session->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-[8px] px-2 py-0.5 rounded-full font-black uppercase tracking-tighter {{ $session->status == 'pago' ? 'bg-green-100 text-green-700' : 'bg-pink-100 text-pink-700' }}">
                                                    {{ $session->status }}
                                                </button>
                                            </form>
                                        </div>

                                        <div class="flex items-center border-l border-pink-100 pl-4 gap-1">
                                            <form action="{{ route('sessions.destroy', $session->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>

                                            @if($session->is_recurrent && $session->status == 'pendente')
                                            <form action="{{ route('sessions.destroyRecursive', $session->id) }}" method="POST" onsubmit="return confirm('Deseja excluir a série recorrente?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-blue-300 hover:text-blue-700 transition">
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

                    <div class="mt-10 flex flex-wrap items-center justify-center gap-1 border-t border-pink-50 pt-6">
                        @foreach(range(1, 12) as $m)
                            @php $isActive = ($month == $m); @endphp
                            <a href="{{ route('patients.show', ['patient' => $patient->id, 'mes' => $m, 'ano' => $year]) }}" 
                               class="px-3 py-1.5 rounded-xl text-[10px] font-bold transition {{ $isActive ? 'bg-pink-500 text-white shadow-md' : 'bg-white text-gray-400 hover:bg-pink-50 border border-gray-100' }}">
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('M') }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="modalSessao" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-[2rem] p-8 max-w-md w-full shadow-2xl border border-pink-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Nova Sessão</h3>
                    <button onclick="document.getElementById('modalSessao').classList.add('hidden')" class="text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <form action="{{ route('sessions.store', $patient->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] uppercase font-black text-gray-400 mb-2 tracking-widest">Dia do Atendimento</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Seg' => 'Monday', 'Ter' => 'Tuesday', 'Qua' => 'Wednesday', 'Qui' => 'Thursday', 'Sex' => 'Friday', 'Sáb' => 'Saturday', 'Dom' => 'Sunday'] as $label => $value)
                                <label class="cursor-pointer">
                                    <input type="radio" name="day_of_week" value="{{ $value }}" onclick="filterCalendar(this.value)" class="hidden peer" required>
                                    <div class="px-3 py-1.5 border border-pink-100 rounded-xl text-[10px] font-bold text-pink-400 peer-checked:bg-pink-500 peer-checked:text-white transition shadow-sm">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="date" name="session_date" id="session_date" value="{{ date('Y-m-d') }}" required class="rounded-xl border-gray-100 bg-gray-50 text-sm">
                        <input type="time" name="session_time" required class="rounded-xl border-gray-100 bg-gray-50 text-sm">
                    </div>

                    <select name="service_type" id="service_selector" onchange="updatePrice(this.value)" class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm">
                        <option value="1">Psicoterapia (R$ 120)</option>
                        <option value="2">Avaliação (R$ 250)</option>
                        <option value="3">Consultoria (R$ 140)</option>
                    </select>

                    <input type="number" name="value" id="service_price" value="120.00" step="0.01" class="w-full rounded-xl border-gray-100 bg-gray-50 font-bold text-pink-600">

                    <div class="flex items-center gap-2 p-3 bg-pink-50/50 rounded-2xl border border-pink-100">
                        <input type="checkbox" name="is_recurrent" id="is_recurrent" class="rounded text-pink-500">
                        <label for="is_recurrent" class="text-[11px] font-bold text-pink-700 italic">Repetir semanalmente até fim do ano</label>
                    </div>

                    <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-2xl font-bold shadow-lg shadow-pink-200">Salvar Sessão</button>
                </form>
            </div>
        </div>

        <script>
            function updatePrice(type) {
                const prices = { '1': '120.00', '2': '250.00', '3': '140.00' };
                document.getElementById('service_price').value = prices[type];
            }

            function filterCalendar(selectedDay) {
                const dateInput = document.getElementById('session_date');
                const dayMap = {
                    'Sunday': 0, 'Monday': 1, 'Tuesday': 2, 'Wednesday': 3, 
                    'Thursday': 4, 'Friday': 5, 'Saturday': 6
                };
                const targetDay = dayMap[selectedDay];

                dateInput.onchange = function() {
                    if (!this.value) return;
                    const dateValue = new Date(this.value + 'T00:00:00');
                    const actualDay = dateValue.getDay();

                    if (actualDay !== targetDay) {
                        alert('Atenção: A data selecionada não é uma ' + selectedDay.replace('Monday','Segunda').replace('Tuesday','Terça').replace('Wednesday','Quarta').replace('Thursday','Quinta').replace('Friday','Sexta').replace('Saturday','Sábado').replace('Sunday','Domingo') + '!');
                        this.value = '';
                    }
                };
                dateInput.value = '';
            }
        </script>
    </div>
</x-app-layout>