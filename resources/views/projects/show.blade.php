<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
         x-data="{ 
             tab: 'Descripción'">
        
        <!-- Navegación de pestañas mejorada -->
        <nav class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <template x-for="(tabName, label) in { 
                Descripción: 'Descripción', 
                Chat: 'Chats', 
                Miembros: 'Miembros' 
            }">
                <button 
                    @click="tab = tabName"
                    :class="{ 'border-blue-600 text-blue-600': tab === tabName }"
                    class="px-4 py-2 border-b-2 hover:border-blue-600"
                    x-text="label"
                ></button>
            </template>
        </nav>
    </div>
</x-app-layout>