CKEDITOR.plugins.add( 'microtags',
{
	beforeInit : function(editor)
	{
		CKEDITOR.config.contentsCss = this.path+'plugin.css';
	},

	init : function( editor )
	{

		editor.addCommand( 'microtags', new CKEDITOR.dialogCommand('dialog'));
		
		editor.addCommand('del',{
			exec : function(editor){
				var element = editor.elementPath().elements[0].$,
					temp = element.innerHTML;
				element.parentNode.removeChild(element);
				editor.insertText(temp);
			}
		});

		editor.ui.addButton( 'Microtags',
		{
			label : 'Добавить тег разметки',
			command : 'microtags',
			icon : this.path + "icons/icon.png",
			toolbar : 'insert'
		});

		if ( editor.contextMenu ) {
			editor.addMenuGroup( 'spanGroup' );
			editor.addMenuItem( 'spanItem', {
				label: 'Удалить тег',
				icon: this.path + 'icons/icon.png',
				command: 'del',
				group: 'spanGroup'
			});

			editor.contextMenu.addListener( function( element ) {
				if (element.getAscendant( 'span', true ) && element.getAttribute('itemprop') != null || element.getAttribute('itemscope') != null) {
					return { spanItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}

//		editor.contextMenu.addListener( function( element ) {
//			if ( element.getAscendant( 'span', true ) && element.getAttribute('itemprop') != null || element.getAttribute('itemscope') != null) {
//				console.log(element.getAttribute('itemprop'));
//				return { testtagItem: CKEDITOR.TRISTATE_OFF };
//			}
//		});

		CKEDITOR.dialog.add( 'dialog', this.path + 'dialogs/microtags.js' );
	},
});
