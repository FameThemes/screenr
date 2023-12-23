(function (api) {

	function isCleanState() {
		var defaultChangesetStatus;

		/*
		 * Handle special case of previewing theme switch since some settings (for nav menus and widgets)
		 * are pre-dirty and non-active themes can only ever be auto-drafts.
		 */
		if ( ! api.state( 'activated' ).get() ) {
			return 0 === api._latestRevision;
		}

		// Dirty if the changeset status has been changed but not saved yet.
		defaultChangesetStatus = api.state( 'changesetStatus' ).get();
		if ( '' === defaultChangesetStatus || 'auto-draft' === defaultChangesetStatus ) {
			defaultChangesetStatus = 'publish';
		}
		if ( api.state( 'selectedChangesetStatus' ).get() !== defaultChangesetStatus ) {
			return false;
		}

		// Dirty if scheduled but the changeset date hasn't been saved yet.
		if ( 'future' === api.state( 'selectedChangesetStatus' ).get() && api.state( 'selectedChangesetDate' ).get() !== api.state( 'changesetDate' ).get() ) {
			return false;
		}

		return api.state( 'saved' ).get() && 'auto-draft' !== api.state( 'changesetStatus' ).get();
	}
	
	
	api.controlConstructor['google-fonts-downloader'] = api.Control.extend({
		ready: function () {
			var control = this;
			
			const inputs = control.container.find( 'input' );
		
			inputs.on('change', function (e) { 
				let values = {};
				inputs.each( function() {
					const input = jQuery( this );
					const name = input.attr( 'data-name' );
					values[ name ] = input.is(':checked') ?  1 : '';
				} );
				control.settings.default.set(JSON.stringify(values));
				
			} );
			
			const ajax = (btn, action) => {
				if (!btn.data('o-text')) {
					btn.data('o-text', btn.html());
				}
				btn.html(control.params.labels.downloading);
				control.container.find( '.ajax-notification' ).remove();
				jQuery.ajax({
					url: window.ajaxurl,
					type: 'get',
					dataType: 'json',
					data: {
						action: control.params.ajax_action,
						doing: action,
					},
					success: function (res) {
			
						if (res.success) {
							control.container.append('<div class="ajax-notification" style="border: 1px solid green; padding: 10px; ">' + res.data.message + '</div>')
						} else {
							control.container.append('<div class="ajax-notification" style="border: 1px solid red; padding: 10px; ">' + res.data.message + '</div>')
						}

					}
				}).always(function () {
					btn.html(btn.data('o-text'));
				});
			}
			
			control.container.on('click', '.download', function (e) {
				e.preventDefault();
				if ( isCleanState() ) {
					const btn = jQuery(this);
					ajax( btn, 'download' );
				} else {
					
					alert( control.params.labels.warning );
				}
				
			});
			
			control.container.on('click', '.clear', function (e) {
				e.preventDefault();
				const btn = jQuery(this);
				ajax( btn, 'clear' );
			});

		},
	});


})(wp.customize);