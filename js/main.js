$(function()
{
	var time = $('form#dropzone').data('time');

	var load			= $('#load');
	var upload_success	= $('#upload_success');
	var upload_failed	= $('#upload_failed');
	var file_error		= $('#file_error');
	var zip_link		= $('#zip_link');

	load			.hide();
	upload_success	.hide();
	upload_failed	.hide();
	file_error		.hide();

	Dropzone.options.dropzone = {
		paramName: 'icon',
		init: function()
		{
			this.on('addedfile', function(file)
			{
				load.fadeIn();
			});

			this.on('success', function(file){});

			this.on('complete', function(file)
			{
				$.getJSON('app_icon.php?time=' + time, function(data)
				{
					load.fadeOut();

					if (data.is_error)
					{
						file_error.fadeIn();
						return false;
					}

					var dl_url = 'http://code.desweb-creation.fr/app-icon/download.php?hash=' + data.hash;

					zip_link.attr('href', dl_url);

					location.href = dl_url;

					upload_success	.fadeIn();
					upload_failed	.fadeOut();
					file_error		.fadeOut();
				});
			});
		},
		accept: function(file, done)
		{
			upload_success	.fadeOut();
			upload_failed	.fadeOut();
			file_error		.fadeOut();

			var is_error = false;

			if (file.size > 1024 * 1024) is_error = true;

			// Check size doesn't work
			//if (file.width != 1024 || file.height != 1024) is_error = true;

			if (file.name.split('.').pop() != 'png' && file.name.split('.').pop() != 'PNG') is_error = true;

			// Missing dpi check !

			if (is_error)
			{
				file_error.fadeIn();

				done('Fichier invalide');
				return false;
			}

			done();
		}
	};
});