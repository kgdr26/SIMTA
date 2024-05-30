@extends('layouts.tempWeb')

@section('kontenPage')
<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="d-flex flex-column-fluid">
			<div class="container-fluid p-0">
				<div class="card card-custom gutter-b">
					{{-- header  --}}
					<div class="card-header">
						<div class="card-title">
							<label>Daftar Log Bimbingan Mahasiswa</label>
						</div>
					</div>
					{{-- end- header  --}}
					{{-- body table  --}}
					<div class="card-body py-10">
						<div class="table-responsive">
							<table class="table table-head-custom table-head-bg table-hover text-center display nowrap" role="grid" id="kt_datatable">
								<thead>
									<tr class="text-center" role="row">
										<th>#</th>
										<th>Tipe Absen</th>
										<th>Tanggal Mulai</th>
										<th>Tanggal Akhir</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody id="formAbsen">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	


@endsection