const objStates = {
	list_states: '',
	UF: ''
}
			
function handleSchedule () {
	const justification = document.querySelector('#justification-select')
	const justification_label = document.querySelector('[for="justification-select"]') 
	const justification_others = document.querySelector('#justification-others') 
	const justification_others_label = document.querySelector('[for="justification-others"]')
	if (document.contains(justification_others)) {
		justification_others.remove()
		justification_others_label.remove()
	}
	if (this.value !== 'Não agendou' && this.value !== 'Cancelou' && this.value !== 'Não compareceu') {
		if (document.contains(justification)) {
			justification.remove()
			justification_label.remove()
		}
	} else if (this.value === 'Cancelou') {
		if (document.contains(justification)) {
			justification.remove()
			justification_label.remove()
		}
		if (!document.contains(justification)) {						
			const div_justification = document.createElement('div')
			div_justification.className = 'form-box row w-100'
			const html = `
				<div class="col-md-12 w-100 ml-3">
					<label id="label-justification" for="justification-select">Motivo do cancelamento</label>
						<select id="justification-select" name="justification-cancellation" class="w-100 ml-3 rounded" required>
							<option value="">-- Selecione --</option>
							<?php
								foreach ($user_justifications_array as $justification) {			
									echo '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
								}
							?>
							<option value="Outros">Outros</option>
							</select>
				</div>
			`
			const select_justification = html.trim()
			div_justification.innerHTML = select_justification
			const parent_node = document.querySelector('#form-container')
			const next_node = document.querySelector('#field-container')
			parent_node.insertBefore(div_justification, next_node)
			document.querySelector('#justification-select').addEventListener('change', handleJustificationOthers, false)
		}
	} else if (this.value === 'Não compareceu') {
		if (document.contains(justification)) {
			justification.remove()
			justification_label.remove()
		}
		if (!document.contains(justification)) {
			const div_justification = document.createElement('div')
			div_justification.className = 'form-box row w-100'
			const html = `
				<div class="col-md-12 w-100 ml-3">
					<label id="label-justification" for="justification-select">Motivo da falta</label>
						<select id="justification-select" name="justification-missing" class="w-100 ml-3 rounded" required>
							<option value="">-- Selecione --</option>
							<?php
								foreach ($user_justifications_array as $justification) {			
									echo '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
								}
							?>
							<option value="Outros">Outros</option>
							</select>
				</div>
			`
			const select_justification = html.trim()
			div_justification.innerHTML = select_justification
			const parent_node = document.querySelector('#form-container')
			const next_node = document.querySelector('#field-container')
			parent_node.insertBefore(div_justification, next_node)
			document.querySelector('#justification-select').addEventListener('change', handleJustificationOthers, false)		
		}
	} else if (this.value === 'Não agendou') {
		if (document.contains(justification)) {
			justification.remove()
			justification_label.remove()
		}
		if (!document.contains(justification)) {
			const div_justification = document.createElement('div')
			div_justification.className = 'form-box row w-100'
			const html = `
				<div class="col-md-12 w-100 ml-3">
					<label id="label-justification" for="justification-select">Motivo de não ter agendado</label>
						<select id="justification-select" name="justification-schedule" class="w-100 ml-3 rounded" required>
							<option value="">-- Selecione --</option>
							<?php
								foreach ($user_justifications_array as $justification) {			
									echo '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
								}
							?>
							<option value="Outros">Outros</option>
						</select>
				</div>
			`
			const select_justification = html.trim()
			div_justification.innerHTML = select_justification
			const parent_node = document.querySelector('#form-container')
			const next_node = document.querySelector('#field-container')
			parent_node.insertBefore(div_justification, next_node)
			document.querySelector('#justification-select').addEventListener('change', handleJustificationOthers, false)
		}
	}
}
		
function  handleJustificationOthers () {
	let justification_others = document.querySelector('#justification-others') 
	const justification_others_label = document.querySelector('[for="justification-others"]')
	if (this.value != 'Outros') {
		if (document.contains(justification_others)) {
			justification_others.remove()
			justification_others_label.remove()
		}
	} else {
		if (!document.contains(justification_others)) {
			const div_others = document.createElement('div')
			div_others.className = 'form-box row w-100'
			const justification_select = document.querySelector('#justification-select')
			const name_justification = justification_select.name
			let html = '<div class="col-md-12 w-100 ml-3">'
			if (name_justification === 'justification-schedule') {
				html += `
					<label for="justification-others">Especifique o motivo</label>
						<textarea id="justification-others" class="align-self-center ml-3 w-100" name="others-schedule" style="resize: none; height: 80px;" required></textarea>
				`
			} else if (name_justification === 'justification-cancellation') {
				html += `
					<label for="justification-others">Especifique o motivo</label>
						<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-cancellation" style="resize: none; height: 80px;" required></textarea>
				`
			} else if (name_justification === 'justification-missing') {
				html += `
					<label for="justification-others">Especifique o motivo</label>
						<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-missing" style="resize: none; height: 80px;" required></textarea>
				`
			}
			html += '</div>'
			const textarea_others = html.trim()
			div_others.innerHTML = textarea_others
			const parent_node = document.querySelector('#form-container')
			const next_node = document.querySelector('#field-container')
			parent_node.insertBefore(div_others, next_node)
		}
	}
}
						
function getCities () {
	const state = document.getElementById('state-select').value
	if (state !== '') {
		$.ajax({
			url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/{UF}/municipios'
		})
	}
}
						
function getStates () {
	return $.ajax({ url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' })
}
						
function getCities (UF) {
	return $.ajax({ url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + UF + '/municipios' })
}
				
function showCities () {
	const cities = document.querySelectorAll('#city-select option')
	const city_select = document.getElementById('city-select')
	const selected_state = document.getElementById('state-select').value
	for (city of cities) {
		city_select.remove(city)
	}
	let option = document.createElement('option')
	option.innerHTML = '-- Selecione --'
	city_select.appendChild(option)		
	if (selected_state === '') return
	getStates().then(res => {
		const selected_state = document.getElementById('state-select').value
		let UF = ''
		for (state of res) {
			if (state.nome == selected_state) {
				UF = state.sigla
				getCities(UF).then(res => {
					for (city of res) {
						let option_city = document.createElement('option')
						option_city.innerHTML = city.nome
						document.getElementById('city-select').appendChild(option_city)
					}
				})
			}
		}
	})
}

function activeSection() {
	const uri = window.location.href
	array_uri = uri.split('/')
	if (array_uri[array_uri.length - 1] === '' || array_uri[array_uri.length - 1] === 'Relatorios') {
		document.querySelector('[href="./"]').classList.add('active')
	} else if (uri.includes('editar.php') || uri.includes('listar.php')) {
		document.querySelector('[href="./listar.php"]').classList.add('active')
		return
	} else if (uri.inclues('relatorio.php')) {
		document.querySelector('[href="./relatorio.php"]').classList.add('active')
	}
}
