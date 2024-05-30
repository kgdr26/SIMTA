@extends('layouts.tempWeb')

@section('kontenPage')

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="d-flex flex-column-fluid">
			<div class="container-fluid p-0">
				<div class="card card-custom gutter-b">
					{{-- header  --}}
					<div class="card-header">
						<div class="card-title">
							<label>Daftar Akun Pengguna</label>
						</div>

						<div class="card-toolbar">
							<button class="btn btn-primary py-2 px-4 font-weight-bolder font-size-m"
								data-toggle="modal" data-target="#akunUserModal"
								onclick="clearForm()">
								<i class="flaticon2-plus icon-sm"></i>Tambah Akun
							</button>
						</div>
					</div>
					{{-- end- header  --}}
					{{-- body table  --}}
					<div class="card-body py-10">
						<div class="table-responsive">
							<table class="table table-head-bg table-hover text-center display nowrap"
								role="grid" id="kt_datatable">
								<thead>
									<tr class="text-center text-dark" role="row">
										<th>No</th>
										<th>Name</th>
										<th>Username</th>
										<th>Email</th>
										<th>Role</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody id="dataAkun">

								</tbody>
							</table>
						</div>
					</div>
					{{-- body table  --}}

					{{-- modal akun user  --}}
					<div class="modal fade" id="akunUserModal" data-backdrop="static" tabindex="-1"
						role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-default" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="akunUserModalLabel">Buat Akun Pengguna
									</h5>
									<button type="button" class="close" data-dismiss="modal"
										onclick="clearForm()" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close"></i>
									</button>
									{{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
								</div>

								<div class="modal-body">
									<div id="page">
										<div class="p2">
											<input type="hidden" id="id_akunUser">
											<div class="mb-3">
												<label for="name" class="form-label">Name</label>
												<input type="text" class="form-control"
													id="name" name="name" required>
											</div>
											<div class="mb-3">
												<label for="username"
													class="form-label">Username</label>
												<input type="text" class="form-control"
													id="username" name="username" required>
											</div>
											<div class="mb-3">
												<label for="email" class="form-label">Email
													address</label>
												<input type="email" class="form-control"
													id="email" name="email" required>
											</div>
											<div class="mb-3">
												<label for="role" class="form-label">Role</label>
												<select class="form-select" id="role"
													name="role" required>
													<option value="" disabled selected>Pilih Role
													</option>
													<option value="admin">Admin</option>
													<option value="dosen">Dosen</option>
													<option value="mahasiswa">Mahasiswa</option>
												</select>
											</div>
											<div class="mb-3">
												<label for="password"
													class="form-label">Password</label>
												<input type="password" class="form-control"
													id="password" name="password" required>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button"
										class="btn btn-light-primary font-weight-bold"onclick="clearForm()"
										data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary font-weight-bold"
										onclick="store()" id="submit">Submit</button>
								</div>
							</div>
						</div>
					</div>
					{{-- modal akun user  --}}
				</div>
			</div>
		</div>

	</div>
</div>
	
@push('scripts')
<script>
	function readDataAkun() {
		$.ajax({
			url: "{{ route('admin.fetch') }}",
			type: "GET",
			success: function(response) {
				console.log(response);

				// Initialize DataTables with received data
				$('#kt_datatable').DataTable({
					dom: 'Bfrtip', // Menambahkan tombol di atas tabel
					buttons: [
						'pdf',
						'excel' // Menambahkan tombol unduhan Excel
					],
					data: response,
					destroy: true, // Destroy existing DataTable instance, if any
					columns: [{
							data: null,
							render: function(data, type, row, meta) {
								return meta.row + 1;
							}
						},
						{
							data: 'name'
						},
						{
							data: 'username'
						},
						{
							data: 'email'
						},
						{
							data: 'role'
						},
						{
							data: null,
							render: function(data, type, row) {
								return `<button type="button" class="btn btn-icon my-2 btn-sm btn-warning" data-toggle="modal" data-target="#akunUserModal" onclick="editData('${row.id_users}')">
												<i class="flaticon2-edit"></i>
											</button>
											<button type="button" class="btn btn-icon my-2 btn-sm btn-danger delete-btn" data-id="${row.id_users}">
												<i class="flaticon2-trash"></i>
											</button>`;
							}
						}
					]
				});

				// Tambahkan event listener untuk tombol hapus setelah tabel diinisialisasi
				$('.delete-btn').on('click', function() {
					var userId = $(this).data('id');
					deleteData(userId);
				});
			},
			error: function(xhr) {
				console.log(xhr.responseText);
			}
		});
	}

	function deleteData(id) {
		if (confirm('Are you sure you want to delete this user?')) {
			$.ajax({
				url: '{{ url('admin/delete') }}/' + id,
				type: 'DELETE',
				data: {
					"_token": "{{ csrf_token() }}"
				},
				success: function(response) {
					console.log(response);
					// Reload data after successful deletion
					readDataAkun();
					window.location.reload();
				},
				error: function(xhr) {
					console.log(xhr.responseText);
				}
			});
		}
	}

	$(document).ready(function() {
		readDataAkun();
	});

	function store(id = null) {
		var name = $('#name').val();
		var username = $('#username').val();
		var email = $('#email').val();
		var role = $('#role').val();
		var password = $('#password').val();
		var url = "{{ route('admin.store') }}";
		var type = "POST";
		if (id) {
			url = "{{ url('admin/update') }}/" + id;
			type = "PUT";
		}
		$.ajax({
			url: url,
			type: type,
			data: {
				name: name,
				username: username,
				email: email,
				role: role,
				password: password,
				_token: $('input[name="_token"]').val()
			},
			success: function(response) {
				// console.log(response);
				$('#akunUserModal').modal('hide');
				window.location.reload();

			},
			error: function(xhr) {
				console.log(xhr.responseText);
			}
		});
	}

	function editData(id) {

		$.ajax({
			url: "{{ url('admin/edit') }}/" + id,
			type: "GET",
			success: function(response) {
				console.log(response);
				var password = response.password;
				$('#akunUserModal').modal('show');
				console.log(response);
				$('#name').val(response.name);
				$('#username').val(response.username);
				$('#email').val(response.email);
				$('#role').val(response.role);
				$('#password').val(response.password);
				console.log($('#submit'));

				// Change the onclick function of the submit button
				var submitButton = $('#submit');
				$('#submit').removeAttr('onclick'); // Remove the inline onclick attribute
				$('#submit').attr('onclick', `store(${id})`);
			},
			error: function(xhr) {
				console.log(xhr.responseText);
			}
		});
	}

	function clearForm() {
		$('#name').val('');
		$('#username').val('');
		$('#email').val('');
		$('#role').val('');
		$('#password').val('');
		$('#submit').removeAttr('onclick');
		$('#submit').attr('onclick', 'store()');
	}
</script>
@endpush

</body>
@endsection
