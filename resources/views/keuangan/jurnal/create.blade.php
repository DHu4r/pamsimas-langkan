<x-layout :tittle="$tittle">
    <div>
        <h2 class="text-slate-700 text-xl font-bold mb-4">Transaksi Mutasi Saldo</h2>

        {{-- Tampilan Halaman Dekstop --}}
        <div class="hidden lg:block md:table w-full text-base text-slate-700 mt-2">
            <div class="flex">
                <a href="/keuangan/jurnal">
                    <div class="group flex items-center justify-center rounded-2xl w-12 h-10 bg-green-300 hover:bg-green-600 hover:outline-1 hover:outline-green-900">
                        <svg class="w-8 h-8 text-gray-800 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 16-4-4 4-4m-6 8-4-4 4-4"/>
                        </svg>          
                    </div>
                </a>
            </div>

            <form action="{{ route('penggunaan_air.store') }}" method="POST" class="mt-5" id="form-penggunaan-air">
                @csrf
                <div class="w-2/12">
                    <label for="akun_debit">Pilih Akun Debit</label>
                    <option value=""></option>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmHapus(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus data ?',
                text: "Data tidak dapat dikembalikan !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#395ff7',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-' + id).submit();
                }
            })
        }

        function formPengguna() {
            return {
                showForm: false,
                formMode: 'tambah',
                isLoading: false,
                formData: {
                    id: null,
                    nama: '',
                    komplek: '',
                    role: '',
                    no_hp: '',
                    password: ''
                },
                init() {
                    this.isLoading = false;
                },
                tambahPengguna() {
                    this.showForm = !this.showForm;
                    if(!this.showForm){
                        this.formMode = 'tambah';
                        this.formData = {
                            id: null,
                            nama: '',
                            komplek: '',
                            role: '',
                            no_hp: '',
                            password: ''
                        }
                    };
                },
                editPengguna(pengguna) {
                    this.showForm = true;
                    this.formMode = 'edit';
                    this.formData = {
                        id: pengguna.id,
                        nama: pengguna.nama,
                        komplek: pengguna.komplek,
                        role: pengguna.role,
                        no_hp: pengguna.no_hp,
                        password: ''
                    };
                }
            }
        }

        function clearForm(){
            const form = document.getElementById('form-pengguna');
            form.reset();
        }

    
    </script>
</x-layout>