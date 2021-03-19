<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">

<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Demo ReFirma Invoker - RENIEC - SEGDI PCM</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

	<!--<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/clientclickonce.js"></script> -->
	<<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js">
		</script>

		<!-- 	<script type="text/javascript" src="http://localhost:8080/refirma_invoker/resources/js/client.js"></script> -->
		<script type="text/javascript">
			//<![CDATA[
			var documentName_ = null;
			//
			window.addEventListener('getArguments', function(e) {
				type = e.detail;
				if (type === 'W') {
					ObtieneArgumentosParaFirmaDesdeLaWeb(); // Llama a getArguments al terminar.
				} else if (type === 'L') {
					ObtieneArgumentosParaFirmaDesdeArchivoLocal(); // Llama a getArguments al terminar.
				}
			});

			function getArguments() {
				arg = document.getElementById("argumentos").value;
				dispatchEventClient('sendArguments', arg);
			}

			window.addEventListener('invokerOk', function(e) {
				type = e.detail;
				if (type === 'W') {
					MiFuncionOkWeb();
				} else if (type === 'L') {
					MiFuncionOkLocal();
				}
			});

			window.addEventListener('invokerCancel', function(e) {
				MiFuncionCancel();
			});

			//::LÓGICA DEL PROGRAMADOR::			
			function ObtieneArgumentosParaFirmaDesdeLaWeb() {
				$.post("controller/postArguments.php", {
					type: "W",
					documentName: "",
					...app.actas[app.index],
					select: app.selected
				}, function(data, status) {

					//alert("Data: " + data + "\nStatus: " + status);
					document.getElementById("argumentos").value = data;
					getArguments();
				});
			}

			function ObtieneArgumentosParaFirmaDesdeArchivoLocal() {
				$.get("controller/getArguments.php", {}, function(data, status) {
					documentName_ = data;
					//Obtiene argumentos
					$.post("controller/postArguments.php", {
						type: "L",
						documentName: documentName_
					}, function(data, status) {
						//alert("Data: " + data + "\nStatus: " + status);
						document.getElementById("argumentos").value = data;
						getArguments();
					});

				});

			}

			function MiFuncionOkWeb() {
				//alert("Documento firmado desde una URL correctamente.");
				app.updEstadoActas(app.index, app.selected, documentName_);
				//document.getElementById("visorPDF").src="controller/certi_estudios/upload/" + documentName_;
			}

			function MiFuncionOkLocal() {
				alert("Documento firmado desde la PC correctamente.");
			}

			function MiFuncionCancel() {
				alert("El proceso de firma digital fue cancelado.");
			}
			//]]>
		</script>
		<style type="text/css">
			footer {
				background-color: #222222;
				position: fixed;
				bottom: 0;
				left: 0;
				right: 0;
				height: 40px;
				text-align: center;
				color: #CCC;
			}

			footer p {
				padding: 14px;
				margin: 0px;
				line-height: 100%;
			}
		</style>
</head>

<body>

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container ">
			<div class="navbar-header center">
				<h1 class="text-center white" style="color: white; text-align: center;"> Demo Componente ReFirma Invoker | Actas de Notas - UNDAC</h1>
			</div>
		</div>
	</nav>

	<!-- Main jumbotron for a primary marketing message or call to action -->
	<div class="jumbotron">
		<div class="container">
		</div>
	</div>

	<div class="container">
		<div id="app">

			<label>Rol:</label>
			<select v-model="selected" class="form-control" id="exampleFormControlSelect1">
				<option value="DO">DOCENTE (09619796 JULIO CESAR LAGOS HUERE)</option>
				<option value="DI">DIRECTOR (20880895 FELIPE YALI RUPAY )</option>
			</select>
			<Hr>
			<label>Firma de Actas:</label>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Códgio</th>
						<th scope="col">Docente</th>
						<th scope="col">Director</th>
						<th scope="col">Curso</th>
						<th scope="col">Opciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(item, index) in actas" :key="item.cod_mod" v-if="item.firmar_do || (selected=='DO')">
						<th scope="row">{{index+1}}</th>
						<td>{{item.cod}}</td>
						<td>{{item.docente}}</td>
						<td>{{item.director}}</td>
						<td>{{item.curso}}</td>
						<td>
							<a class="btn btn-success" href="#" role="button" @click.prevent="firmar(index)" v-if="(selected=='DO' && !item.firmar_do) || (selected=='DI' && item.firmar_do && !item.firmar_di)">Firmar</a>
							<a class="btn btn-primary" target="_blank" :href="getUrlSeeDocument(index, item.firmar_do, item.firmar_di)" role="button" v-else>Ver</a>
						</td>
					</tr>

				</tbody>
			</table>

		
		</div>
		<Hr>

		<br><br><br><br>

		<hr>

		<footer>
			<p>Registro Nacional de Identificación y Estado Civil - RENIEC / <a href="http://www.gobiernodigital.gob.pe/" style="color: #CCC;"> Secretaría de Gobierno Digital SeGDi-PCM </a></p>
		</footer>

		<input type="hidden" id="argumentos" value="" />
		<div id="addComponent"></div>
	</div>
	<!-- /container -->
	<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

	<script>
		var app = new Vue({
			el: '#app',
			data: {
				actas_base: [{
						cod: 'act1',

						docente: '09619796',
						director: '20880895',
						curso: 'Fisica 1',


						firmar_do: 0,
						firmar_di: 0

					},
					{
						cod: 'act2',

						docente: '09619796',
						director: '20880895',
						curso: 'Estadistica',

						firmar_do: 0,
						firmar_di: 0

					},
					{
						cod: 'act3',

						docente: '09619796',
						director: '20880895',
						curso: 'Geopolitica',

						firmar_do: 0,
						firmar_di: 0

					},
					{
						cod: 'act4',

						docente: '09619796',
						director: '20880895',
						curso: 'Fisica 1',

						firmar_do: 0,
						firmar_di: 0

					},
					{
						cod: 'act5',

						docente: '09619796',
						director: '20880895',
						curso: 'Matematica',

						firmar_do: 0,
						firmar_di: 0

					},
					{
						cod: 'act6',

						docente: '09619796',
						director: '20880895',
						curso: 'Economia',

						firmar_do: 0,
						firmar_di: 0

					},
					{
						cod: 'act7',

						docente: '09619796',
						director: '20880895',
						curso: 'Libre',

						firmar_do: 0,
						firmar_di: 0

					},

				],
				index: '',
				selected: 'DO',
				actas: [],


				todos: [{
						text: 'Learn JavaScript'
					}

				]
			},
			created: function() {
				//this.actas=this.actas_base;

				if (!localStorage.getItem('undac_actas')) {
					localStorage.setItem('undac_actas', JSON.stringify(this.actas_base));
				}
				this.actas = JSON.parse(localStorage.getItem('undac_actas'));
				//no aconsejo trabajar con jquery
			
			},
			methods: {
				guardaCambiosEmDB() {
					localStorage.setItem('undac_actas', JSON.stringify(this.actas));
				},
				firmar: function(index) {
					this.index = index;

					initInvoker('W')
				},
				updEstadoActas: function(index, selected, documentName_) {
					if (selected == 'DO') {
						this.actas[index].firmar_do = 1;
					} else if (selected == 'DI') {
						this.actas[index].firmar_di = 1;
					}
					this.guardaCambiosEmDB();
					Swal.fire({
						icon: 'success',
						title: 'El acta se firmó correctamente. Para verificar presione en ver.	',
						showConfirmButton: false,
						timer: 3000
					})
				},
				getUrlSeeDocument: function(index, firmar_do, firmar_di) {
					let r = 'documents/' + this.actas[index].cod;
					if (firmar_do) {
						r += ('-' + this.actas[index].docente);
					}

					if (firmar_di) {
						r += ('-' + this.actas[index].director);
					}
					r += '.pdf';
					return r;
				}

			},
		})
	</script>
</body>

</html>