<x-app-layout>
    <div class="py-12 bg-[#FDF2F4] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-serif text-gray-800">Editar Perfil: <span class="text-pink-500">{{ $patient->name }}</span></h2>
                <a href="{{ route('pacientes') }}" class="bg-white text-pink-500 border border-pink-200 px-6 py-2 rounded-full text-sm font-bold shadow-sm hover:bg-pink-50 transition flex items-center">
                    <span class="mr-2">←</span> Voltar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-pink-100 p-8">
                <form action="{{ route('patients.update', $patient->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                            <input type="text" name="name" value="{{ old('name', $patient->name) }}" required 
                                   class="w-full rounded-full border-gray-100 bg-gray-50 focus:ring-pink-300 focus:border-pink-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $patient->email) }}" 
                                   class="w-full rounded-full border-gray-100 bg-gray-50 focus:ring-pink-300 focus:border-pink-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone/WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" 
                                   class="w-full rounded-full border-gray-100 bg-gray-50 focus:ring-pink-300 focus:border-pink-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                            <input type="text" name="cpf" value="{{ old('cpf', $patient->cpf) }}" 
                                   class="w-full rounded-full border-gray-100 bg-gray-50 focus:ring-pink-300 focus:border-pink-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $patient->birth_date) }}" 
                                   class="w-full rounded-full border-gray-100 bg-gray-50 focus:ring-pink-300 focus:border-pink-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cidade / Estado</label>
                            <input type="text" name="city_state" value="{{ old('city_state', $patient->city_state) }}" 
                                   placeholder="Ex: São Paulo, SP" 
                                   class="w-full rounded-full border-gray-100 bg-gray-50 focus:ring-pink-300 focus:border-pink-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observações Clínicas</label>
                            <textarea name="observations" rows="3" 
                                      class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-pink-300 focus:border-pink-300">{{ old('observations', $patient->observations) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 flex justify-between items-center">
                        <button type="button" onclick="confirmDelete()" class="text-red-400 hover:text-red-600 text-sm font-medium flex items-center">
                            <span class="mr-1">🗑️</span> Excluir Paciente Permanentemente
                        </button>

                        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-10 py-3 rounded-full font-bold shadow-lg transition transform hover:scale-105">
                            Atualizar Dados
                        </button>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Atenção: Você está prestes a excluir todos os registros deste paciente. Deseja continuar?')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
</x-app-layout>