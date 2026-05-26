<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif text-left">
        
        <div class="max-w-5xl mx-auto mb-6 no-print">
            <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center text-[#8C846C] hover:text-[#766f5a] font-bold text-sm transition group">
                <div class="bg-white p-2 rounded-full shadow-sm border border-[#E1D3C1] mr-3 group-hover:bg-[#E1D3C1] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                Voltar para o Prontuário
            </a>
        </div>

        <div id="conteudo-imprimivel" class="max-w-5xl mx-auto bg-white p-16 shadow-2xl rounded-[1rem] border border-[#E1D3C1] relative">
            
            <div class="text-center mb-12">
                <div class="flex justify-center mb-4">
                    <span class="text-4xl text-[#8C846C]">Ψ</span>
                </div>
                <h1 class="text-xl text-[#8C846C] font-bold leading-tight uppercase tracking-tighter">MSc. Lydia Maria Sena Lima e Santos</h1>
                <p class="text-sm text-[#8C846C]/80 italic font-medium">Psicóloga Clínica e Neuropsicologia</p>
                <p class="text-xs text-[#8C846C]/60 font-black uppercase tracking-[0.2em]">CRP 04/41542</p>
            </div>

            <div class="border-b-2 border-[#8C846C]/30 mb-10 pb-3 flex justify-between items-end">
                <p class="text-[#8C846C] font-bold italic text-base">
                    Nome: <span class="text-[#8C846C] not-italic font-black text-xl ml-2 tracking-tight uppercase">{{ $patient->name }}</span>
                </p>
                <p class="text-[#8C846C] font-bold italic text-sm">
                    CPF: <span class="text-[#8C846C] not-italic font-black ml-1">{{ $patient->cpf ?? 'Não cadastrado' }}</span>
                </p>
            </div>

            <form action="{{ route('patients.plan.store', $patient->id) }}" method="POST" class="mb-10 no-print" id="form-plano">
                @csrf
                <div class="grid grid-cols-6 gap-0 border-2 border-[#8C846C] rounded-xl overflow-hidden shadow-sm">
                    <div class="col-span-1 bg-[#F9F6F3] p-3 border-r border-[#8C846C]">
                        <label class="block text-[9px] font-black uppercase text-[#8C846C] mb-1">Data</label>
                        <input type="date" name="date" id="input_date" value="{{ date('Y-m-d') }}" class="w-full bg-transparent border-0 p-0 text-sm font-bold text-[#8C846C] focus:ring-0">
                    </div>
                    <div class="col-span-3 bg-white p-3 border-r border-[#8C846C]">
                        <label class="block text-[9px] font-black uppercase text-[#8C846C] mb-1">Avaliação e Evolução Clínica</label>
                        <textarea name="clinical_evolution" id="input_evolution" rows="3" class="w-full border-0 focus:ring-0 text-sm p-0 text-gray-700 placeholder-[#8C846C]/30" placeholder="Descreva a evolução..."></textarea>
                    </div>
                    <div class="col-span-2 bg-white p-3 flex flex-col justify-between">
                        <div>
                            <label class="block text-[9px] font-black uppercase text-[#8C846C] mb-1">Objetivos Terapêuticos</label>
                            <textarea name="therapeutic_objectives" id="input_objectives" rows="3" class="w-full border-0 focus:ring-0 text-sm p-0 text-[#8C846C] italic placeholder-[#8C846C]/30" placeholder="Metas..."></textarea>
                        </div>
                        <button type="submit" id="btn-submit" class="mt-4 bg-[#8C846C] text-white text-[10px] font-black py-2 rounded-lg uppercase hover:bg-[#766f5a] transition shadow-md">Adicionar à Ficha</button>
                    </div>
                </div>
            </form>

            <table class="w-full border-collapse border-2 border-[#8C846C]">
                <thead>
                    <tr class="bg-[#F9F6F3] text-[#8C846C] text-[10px] uppercase font-black tracking-widest">
                        <th class="border-2 border-[#8C846C] p-3 w-28 text-center">Data</th>
                        <th class="border-2 border-[#8C846C] p-3 text-center">Avaliação Psicográfica e Evolução Clínica</th>
                        <th class="border-2 border-[#8C846C] p-3 w-64 text-center">Objetivos Terapêuticos</th>
                        <th class="border-2 border-[#8C846C] p-3 w-20 text-center no-print">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($patient->treatmentPlans()->orderBy('date', 'desc')->get() as $plan)
                        <tr class="group hover:bg-[#F9F6F3]/20 transition">
                            <td class="border-2 border-[#8C846C] p-4 text-center font-black text-[#8C846C]">{{ $plan->date->format('d/m/Y') }}</td>
                            <td class="border-2 border-[#8C846C] p-4 leading-relaxed text-justify">{{ $plan->clinical_evolution }}</td>
                            <td class="border-2 border-[#8C846C] p-4 italic text-[#8C846C] font-medium">{{ $plan->therapeutic_objectives }}</td>
                            <td class="border-2 border-[#8C846C] p-2 text-center no-print">
                                <div class="flex flex-col gap-2 items-center justify-center">
                                    {{-- EDITAR --}}
                                    <button onclick="editarEntrada({{ json_encode($plan) }})" class="text-[#8C846C]/50 hover:text-[#8C846C] transition" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    
                                    {{-- EXCLUIR --}}
                                    <form action="{{ route('patients.plan.destroy', $plan->id) }}" method="POST" id="delete-plan-{{ $plan->id }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmarExclusaoPlano({{ $plan->id }})" class="text-red-200 hover:text-red-500 transition" title="Excluir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="border-2 border-[#8C846C] p-10 text-center italic text-[#8C846C]/30">Nenhum registro encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-16 text-center text-[10px] text-[#8C846C]/60 font-black uppercase tracking-widest">
                <p>Rua: Sete de setembro, 923, Centro. Muzambinho – MG | (35) 3571-3142 / 98704-2011</p>
            </div>
        </div>
    </div>

    <button onclick="gerarPdfPlano()" class="fixed bottom-10 right-10 bg-[#8C846C] text-white p-4 rounded-full shadow-2xl no-print hover:scale-110 transition flex items-center justify-center z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
    </button>

    <style>
        @media print {
            body * { visibility: hidden !important; }
            #conteudo-imprimivel, #conteudo-imprimivel * { visibility: visible !important; }
            #conteudo-imprimivel { position: absolute !important; left: 0 !important; top: 0 !important; width: 100% !important; margin: 0 !important; padding: 0 !important; border: none !important; box-shadow: none !important; }
            .no-print { display: none !important; height: 0 !important; }
            body, html, main { background: white !important; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const swalLydia = { confirmButtonColor: '#8C846C', cancelButtonColor: '#E1D3C1', borderRadius: '2rem', customClass: { popup: 'rounded-[2rem]', title: 'font-serif italic text-[#8C846C]' } };

        function editarEntrada(plan) {
            const form = document.getElementById('form-plano');
            const btn = document.getElementById('btn-submit');
            
            // Muda a rota do formulário para o método Update
            form.action = `/plano-terapeutico/${plan.id}`;
            
            // Adiciona o campo _method PUT se não existir
            if(!form.querySelector('input[name="_method"]')) {
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }

            // Preenche os campos com os dados atuais
            document.getElementById('input_date').value = plan.date.split('T')[0];
            document.getElementById('input_evolution').value = plan.clinical_evolution;
            document.getElementById('input_objectives').value = plan.therapeutic_objectives;

            // Visual do botão de salvar
            btn.innerText = "Atualizar Registro";
            btn.classList.replace('bg-[#8C846C]', 'bg-[#7C9A92]');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function confirmarExclusaoPlano(id) {
            Swal.fire({
                title: 'Remover registro?',
                text: "Esta entrada da ficha será excluída permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir',
                ...swalLydia
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-plan-' + id).submit();
                }
            })
        }

        @if(session('success'))
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif
        
        function gerarPdfPlano() {
        Swal.fire({
            title: 'Gerar PDF Oficial?',
            text: "O documento será gerado com todos os registros atuais.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#8C846C',
            confirmButtonText: 'Sim, gerar PDF',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open("{{ route('patients.plan.pdf', $patient->id) }}", '_blank');
            }
        });
    }
    </script>
</x-app-layout>