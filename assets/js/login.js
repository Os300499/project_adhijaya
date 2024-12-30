var form = document.getElementById('loginForm');

var loading = document.getElementById('loading');
const Toast = Swal.mixin({
	toast: true,
	position: "top-end",
	showConfirmButton: false,
	timer: 1500,
	timerProgressBar: true,
	didOpen: (toast) => {
		toast.onmouseenter = Swal.stopTimer;
		toast.onmouseleave = Swal.resumeTimer;
	}
});
const login = (event) => {
	event.preventDefault();
	var formData = new FormData($("#loginForm")[0]);
	$.ajax({
		url: `${BASEURL}Login/sing_in`,
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		beforeSend: () => {
			loading.classList.remove('hide');
			loading.classList.add('loading');
		},
		success: function(res) {
			setTimeout(()=>{
				loading.classList.remove('loading');
				loading.classList.add('hide');
				Toast.fire({
					icon: res.data.status,
					title: res.data.pesan
				});
				if (res.data.status === 'success'){
					setTimeout(()=>{
						window.location = '<?=BASEURL?>admin_page/';
					},1500)
				}
			}, 1000);
			console.log(res)
		},
		error: function(xhr, status, error) {
			setTimeout(()=>{
				loading.classList.remove('loading');
				loading.classList.add('hide');
				Toast.fire({
					icon: "error",
					title: "Terjadi kesalahan periksa kembali jaringan anda"
				});
			}, 1000);
		}
	});
}

form.addEventListener("submit", myFunction);
