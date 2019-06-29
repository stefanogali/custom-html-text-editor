var clsWies = function(_objInit){
	var content = '';
	var attribute = '';
	var isHtmlTag = '';
	var htmlContent = '';

	_objInit.linkText = _objInit.selector + ' ' + 'a';

	function getAttribute(clickedEl){
		var attribute = $(clickedEl).data('attribute');
		return attribute;
	}

	function getContent(clickedEl){
		var content = $(clickedEl).html();
		return content;
	}

	function sendData(buttonId,content,attribute){
		$(_objInit.popup).fadeIn(350);
		$(buttonId).one("click" , function(){
			if (this.id == 'save-change') {
				$.ajax({
					type: "POST",
					url: "includes/service.php",
					data: { value: content, attr: attribute },
					dataType : 'json',
					success: function(result){
						if(!result.response){
							$('[data-attribute ="' + attribute + '"]').html('Error: ' + result.status + '<br>' + result.message);
							$(_objInit.selector).unbind('click');
						}
						if(result.response){
							$('[data-attribute ="' + attribute + '"]').html(result.message);
							$(_objInit.linkText).css('pointer-events', 'none');
						}
					}
				})
			}else{
				//unsavedContent = replaceCharacters(unsavedContent);
				$('[data-attribute ="' + attribute + '"]').html(htmlContent);
			}
			$(_objInit.confirmButton).unbind('click');
			$(_objInit.popup).fadeOut(350);
		})
	}

	function replaceCharacters(textToReplace){
		var replaceChars = {
			'<b><i>':'[bi]<span class = "red">',
			'</i></b>':'</span>[/bi]',
			'<i><u>':'[iu]<span class = "red">',
			'</u></i>':'</span>[/iu]',
			'<b>':'[b]<span class = "red">',
			'</b>':'</span>[/b]',
			'<u>':'[u]<span class = "red">',
			'</u>':'</span>[/u]',
			'<i>':'[i]<span class = "red">',
			'</i>':'</span>[/i]'
		};
		var replaceTags = textToReplace.replace(/<b><i>|<\/i><\/b>|<i><u>|<\/u><\/i>|<b>|<\/b>|<u>|<\/u>|<i>|<\/i>/g,function(match) {
			return replaceChars[match];
		})
		replaceTags = replaceTags.replace(/<a href="([^"]+)"(.*?)>(.*?)(<\/a>)/g, '[link=$1]<span class = "red">$3</span>[/l]');
		return replaceTags;
	}

	//remove formatting on paste
	function onPaste(element){
		[].forEach.call(document.querySelectorAll(element), function (el) {
			el.addEventListener('paste', function(e) {
				e.preventDefault();
				var text = e.clipboardData.getData('text/plain').trim();
				document.execCommand('insertHTML', false, text);
			}, false);
		});
	}
	
	//clear formatting on paste
	onPaste(_objInit.selector);

	this.init = function(){

		//set links not followable to avoid errors with contenteitable div
		$(_objInit.linkText).css('pointer-events', 'none');

		$(_objInit.selector).on('click', function(){
			$(_objInit.selector).removeClass('selected');
			$(this).addClass('selected');
			$(this).attr('contenteditable', 'true');
		})

		$('.response-value').each(function(){
			if ($(this).text() == 0){
				$(_objInit.selector).unbind('click');
			}
		})
		$(_objInit.selector).on('focusin', function() {
			attribute = getAttribute($(this));
			content = getContent($(this));
			htmlContent = content;		
			var replaceTags = replaceCharacters(content);
			if (content != replaceTags){
				$('[data-attribute = "' + attribute + '"]').html(replaceTags);
				content = getContent($(this));
			}
			isHtmlTag = false;
			$('[data-attribute = "' + attribute + '"]').on('focusout', function() {
				$(_objInit.selector).removeAttr('contenteditable').removeClass('selected');
				if (content != $(this).html()){
					//unsavedContent = content;
					content = $(this).html();
					attribute = $(this).data('attribute');
					//ajax call
					sendData(_objInit.confirmButton, content, attribute);
					isHtmlTag = true;
				}
				if (!isHtmlTag){
					//attribute = $(this).data("attribute");
					attribute = getAttribute($(this));
					$('[data-attribute = "' + attribute + '"]').html(htmlContent);
					content = $(this).html();
				}
			})
		})

		$(document).on('keydown','[contenteditable=true]',function(e){
			if (e.keyCode == 13) {
				e.preventDefault();
				// insert 2 br tags (if only one br tag is inserted the cursor won't go to the second line)
				document.execCommand('insertHTML', false, '<br><br>');
				console.log('return key clicked');
				// prevent the default behaviour of return key pressed
			return false;
			}
		})
	}
}


