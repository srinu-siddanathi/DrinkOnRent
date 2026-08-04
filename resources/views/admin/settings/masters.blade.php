@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-5">
        <h2 class="text-lg font-semibold text-gray-900">Area and Spare Parts Management</h2>
        <p class="text-sm text-gray-500 mt-1">Manage both masters from one screen. Add and edit actions are handled in modal dialogs.</p>
    </div>

    <div id="masterAlert" class="hidden rounded-md px-4 py-3 text-sm font-medium"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section class="bg-white shadow rounded-lg">
            <div class="px-4 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Areas</h3>
                <button type="button" onclick="openMasterModal('areas', 'create')" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Add Area
                </button>
            </div>
            <div class="relative">
                <div id="areasLoader" class="hidden absolute inset-0 bg-white/70 backdrop-blur-sm z-10 flex items-center justify-center">
                    <div class="h-7 w-7 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin"></div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="areasTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
                <div id="areasPagination" class="px-4 py-3 border-t border-gray-200"></div>
            </div>
        </section>

        <section class="bg-white shadow rounded-lg">
            <div class="px-4 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Spare Parts</h3>
                <button type="button" onclick="openMasterModal('spare-parts', 'create')" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Add Spare Part
                </button>
            </div>
            <div class="relative">
                <div id="spare-partsLoader" class="hidden absolute inset-0 bg-white/70 backdrop-blur-sm z-10 flex items-center justify-center">
                    <div class="h-7 w-7 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin"></div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="spare-partsTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
                <div id="spare-partsPagination" class="px-4 py-3 border-t border-gray-200"></div>
            </div>
        </section>
    </div>
</div>

<div id="masterModal" class="hidden fixed inset-0 z-50 transition-opacity duration-300 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.75);">
    <div id="masterModalPanel" class="relative p-0 border-0 shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0 -translate-y-10 overflow-hidden" style="width: min(420px, calc(100vw - 2rem)); max-width: 420px;">
        <div class="flex items-center justify-between px-4 py-3 bg-blue-800">
            <h4 id="masterModalTitle" class="text-sm font-semibold text-white"></h4>
            <button type="button" onclick="closeMasterModal()" class="text-white hover:text-gray-200">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="masterForm" class="px-4 py-4 space-y-3">
            <div>
                <label for="masterName" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="masterName" name="name" type="text" required maxlength="255"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p id="masterFormError" class="hidden mt-1 text-sm text-red-500"></p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeMasterModal()" class="px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">Cancel</button>
                <button id="masterSubmitButton" type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <span id="masterSubmitButtonText">Save</span>
                    <span id="masterSubmitLoader" class="hidden h-4 w-4 ml-2 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const state = {
        areas: { page: 1, lastPage: 1, data: [] },
        'spare-parts': { page: 1, lastPage: 1, data: [] },
        modal: { type: null, mode: 'create', id: null },
    };

    const endpoints = {
        areas: {
            list: '{{ route('admin.settings.areas.index') }}',
            store: '{{ route('admin.settings.areas.store') }}',
            update: '{{ route('admin.settings.areas.update', ['area' => '__ID__']) }}',
            destroy: '{{ route('admin.settings.areas.destroy', ['area' => '__ID__']) }}',
        },
        'spare-parts': {
            list: '{{ route('admin.settings.spare-parts.index') }}',
            store: '{{ route('admin.settings.spare-parts.store') }}',
            update: '{{ route('admin.settings.spare-parts.update', ['sparePart' => '__ID__']) }}',
            destroy: '{{ route('admin.settings.spare-parts.destroy', ['sparePart' => '__ID__']) }}',
        }
    };

    function showAlert(message, type = 'success') {
        const alertBox = document.getElementById('masterAlert');
        alertBox.textContent = message;
        alertBox.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
        alertBox.classList.add(type === 'success' ? 'bg-green-100' : 'bg-red-100');
        alertBox.classList.add(type === 'success' ? 'text-green-700' : 'text-red-700');

        setTimeout(() => {
            alertBox.classList.add('hidden');
        }, 3500);
    }

    function setGridLoader(type, show) {
        document.getElementById(`${type}Loader`)?.classList.toggle('hidden', !show);
    }

    function setFormLoading(show) {
        document.getElementById('masterSubmitButton')?.toggleAttribute('disabled', show);
        document.getElementById('masterSubmitLoader')?.classList.toggle('hidden', !show);
    }

    function endpointWithId(type, key, id) {
        return endpoints[type][key].replace('__ID__', String(id));
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function escapeForSingleQuote(value) {
        return String(value ?? '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    }

    async function fetchMasterList(type, page = 1) {
        setGridLoader(type, true);
        try {
            const response = await fetch(`${endpoints[type].list}?page=${page}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            const payload = await response.json();

            state[type].page = payload.current_page || 1;
            state[type].lastPage = payload.last_page || 1;
            state[type].data = payload.data || [];

            renderMasterTable(type);
            renderPagination(type);
        } catch (error) {
            console.error(error);
            showAlert(`Unable to load ${type.replace('-', ' ')} list.`, 'error');
        } finally {
            setGridLoader(type, false);
        }
    }

    function renderMasterTable(type) {
        const tbody = document.getElementById(`${type}TableBody`);
        const items = state[type].data;
        if (!tbody) {
            return;
        }

        if (!items.length) {
            tbody.innerHTML = '<tr><td colspan="2" class="px-4 py-6 text-center text-sm text-gray-500">No records found.</td></tr>';
            return;
        }

        tbody.innerHTML = items.map((item) => `
            <tr>
                <td class="px-4 py-3 text-sm text-gray-900">${escapeHtml(item.name)}</td>
                <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                    <button type="button" onclick="openMasterModal('${type}', 'edit', ${item.id}, '${escapeForSingleQuote(item.name)}')" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                    <button type="button" onclick="deleteMaster('${type}', ${item.id})" class="ml-3 text-red-600 hover:text-red-800 font-medium">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    function renderPagination(type) {
        const box = document.getElementById(`${type}Pagination`);
        if (!box) {
            return;
        }

        const currentPage = state[type].page;
        const lastPage = state[type].lastPage;

        if (lastPage <= 1) {
            box.innerHTML = '<p class="text-sm text-gray-500">Page 1</p>';
            return;
        }

        let pages = '';
        for (let page = 1; page <= lastPage; page++) {
            pages += `
                <button type="button" onclick="fetchMasterList('${type}', ${page})"
                    class="px-3 py-1.5 rounded-md text-sm ${page === currentPage ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'}">
                    ${page}
                </button>
            `;
        }

        box.innerHTML = `
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <button type="button" onclick="fetchMasterList('${type}', ${Math.max(currentPage - 1, 1)})"
                    class="px-3 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 ${currentPage === 1 ? 'opacity-40 pointer-events-none' : ''}">
                    Previous
                </button>
                <div class="flex items-center gap-2">${pages}</div>
                <button type="button" onclick="fetchMasterList('${type}', ${Math.min(currentPage + 1, lastPage)})"
                    class="px-3 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 ${currentPage === lastPage ? 'opacity-40 pointer-events-none' : ''}">
                    Next
                </button>
            </div>
        `;
    }

    function openMasterModal(type, mode, id = null, name = '') {
        state.modal = { type, mode, id };

        document.getElementById('masterModal').classList.remove('hidden');
        document.getElementById('masterModalTitle').textContent = `${mode === 'create' ? 'Add' : 'Edit'} ${type === 'areas' ? 'Area' : 'Spare Part'}`;
        document.getElementById('masterSubmitButtonText').textContent = mode === 'create' ? 'Create' : 'Update';
        document.getElementById('masterName').value = name || '';
        hideFormError();

        setTimeout(() => {
            const modalPanel = document.getElementById('masterModalPanel');
            if (modalPanel) {
                modalPanel.classList.remove('scale-95', 'opacity-0', '-translate-y-10');
            }
        }, 10);
    }

    function closeMasterModal() {
        const modalPanel = document.getElementById('masterModalPanel');
        if (modalPanel) {
            modalPanel.classList.add('scale-95', 'opacity-0', '-translate-y-10');
        }

        setTimeout(() => {
            document.getElementById('masterModal').classList.add('hidden');
            document.getElementById('masterForm').reset();
            hideFormError();
        }, 300);
    }

    function showFormError(message) {
        const errorBox = document.getElementById('masterFormError');
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    }

    function hideFormError() {
        const errorBox = document.getElementById('masterFormError');
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    }

    async function submitMasterForm(event) {
        event.preventDefault();
        hideFormError();

        const name = document.getElementById('masterName').value.trim();
        if (!name) {
            showFormError('Name is required.');
            return;
        }

        const { type, mode, id } = state.modal;
        const method = mode === 'create' ? 'POST' : 'PUT';
        const url = mode === 'create' ? endpoints[type].store : endpointWithId(type, 'update', id);

        setFormLoading(true);

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify({ name }),
            });

            const payload = await response.json();

            if (!response.ok) {
                const firstError = payload?.errors ? Object.values(payload.errors)[0]?.[0] : null;
                throw new Error(firstError || payload.message || 'Operation failed.');
            }

            closeMasterModal();
            showAlert(payload.message || 'Saved successfully.');
            await fetchMasterList(type, state[type].page);
        } catch (error) {
            showFormError(error.message || 'Unable to save.');
        } finally {
            setFormLoading(false);
        }
    }

    async function deleteMaster(type, id) {
        if (!confirm('Delete this item?')) {
            return;
        }

        setGridLoader(type, true);

        try {
            const response = await fetch(endpointWithId(type, 'destroy', id), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
            });

            const payload = await response.json();

            if (!response.ok) {
                throw new Error(payload.message || 'Unable to delete.');
            }

            showAlert(payload.message || 'Deleted successfully.');
            const safePage = Math.min(state[type].page, state[type].lastPage);
            await fetchMasterList(type, safePage);
        } catch (error) {
            showAlert(error.message || 'Unable to delete item.', 'error');
        } finally {
            setGridLoader(type, false);
        }
    }

    document.getElementById('masterForm').addEventListener('submit', submitMasterForm);
    document.getElementById('masterModal').addEventListener('click', function (event) {
        if (event.target.id === 'masterModal') {
            closeMasterModal();
        }
    });

    fetchMasterList('areas');
    fetchMasterList('spare-parts');
</script>
@endsection
