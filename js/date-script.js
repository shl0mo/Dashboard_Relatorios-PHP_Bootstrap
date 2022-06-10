const interval = setInterval(() => {
	if (document.contains(document.querySelector('#status-select'))) {
		document.querySelector('#status-select').addEventListener('change', handleSchedule, false)
			let date = new Date()
			const year = date.getFullYear()
			let month = date.getMonth() + 1
			let day = date.getDate()
			if (String(day).length == 1) day = '0' + day
			if (String(month).length == 1) month = '0' + month
			document.querySelector('#date-input').value = day + '/' + month + '/' + year
			getStates().then(res => {
				for (state of res) {
					let option_state = document.createElement('option')
					option_state.innerHTML = state.nome
					document.getElementById('state-select').appendChild(option_state)
				}
			})
			clearInterval(interval)
		}
}, 100)
