/* ============================================================
 * jquery.modal.search.js  搜索页面用地区js
 * ============================================================
 * Copyright 74cms.
 * ============================================================ */
!function($) {

	var backdropLayerTpl = '<div class="modal_backdrop fade J_dismiss_modal"></div>';
	var htmlLayerTpl = [
			'<div class="modal">',
	            '<div class="modal_dialog">',
	                '<div class="modal_content pie_about">',
	                    '<div class="modal_header">',
							'<span class="title J_modal_title"></span>',
	                        '<span class="max_remind J_modal_max"></span>',
	                        '<a href="javascript:;" class="close J_dismiss_modal"></a>',
						'</div>',
	                    '<div class="modal_body">',
	                    	'<div class="listed_group" id="J_listed_group">',
	                    		'<div class="left_text">已选择：</div>',
	                    		'<div class="center_text" id="J_listed_content"></div>',
	                    		'<a href="javascript:;" class="right_text" id="J_listed_clear">清空</a>',
	                    		'<div class="clear"></div>',
	                    	'</div>',
	                    	'<div class="J_modal_content"></div>',
	                    '</div>',
	                    '<div class="modal_footer">',
	                        '<div class="res_add_but">',
	                        	'<div class="butlist">',
	                            	'<div class="btn_blue J_hoverbut btn_100_38 J_btnyes">确 定</div>',
	                            '</div>',
	                            '<div class="butlist">',
	                            	'<div class="btn_lightgray J_hoverbut btn_100_38 J_dismiss_modal J_btncancel">取 消</div>',
	                            '</div>',
	                            '<div class="clear"></div>',
	                        '</div>',
	                    '</div>',
	                    '<input type="hidden" class="J_btnload" />',
	                '</div>',
	            '</div>',
	        '</div>'
		].join('');

	// 点击显示地区分类
	$('#showSearchModal').die().live('click', function() {
		var titleValue = $(this).data('title');
		var multipleValue = eval($(this).data('multiple'));
		var maxNumValue = eval($(this).data('maxnum'));
		var widthValue = eval($(this).data('width'));
		var isSpell = app_spell;
		var htmlCity = '';
		var isSubsite = eval(qscms.is_subsite); // 是否是分站
    var subsiteLevelNum = eval(qscms.subsite_level);
		var subsiteLevel1 = true; // 是否是一级分站
    if (!isSubsite) { // 如果不是分站 一级分站始终为false
      subsiteLevel1 = false;
    } else {
      if (subsiteLevelNum > 1) { // 如果是分站，分站级数大于1 则为false
        subsiteLevel1 = false;
      }
    }
    var subsiteLevel1Width = 372;
		if (isSubsite) {
      if (isSpell) { // 拼音
        if (!subsiteLevel1) { // 分站是二级
          if (QS_city_spell_parent) {
            htmlCity += '<div class="modal_body_box modal_body_box3">';
            htmlCity += '<div class="left_box">';
            htmlCity += '<ul class="list_nav">';
            for (var i = 0; i < QS_city_spell_parent.length; i++) {
              if (QS_city_spell_parent[i].split(',')) {
                var iArray = QS_city_spell_parent[i].split(',');
                htmlCity += [
                  '<li class="J_list_city_parent" data-code="' + iArray[0] + '" data-title="' + iArray[1] + '">',
                  '<label>' + iArray[1] + '</label>',
                  '</li>'
                ].join('');
              };
            };
            htmlCity += '</ul>';
            htmlCity += '</div>';
            htmlCity += '<div class="right_box">';
            if (QS_city_spell_parent) {
              for (var i = 0; i < QS_city_spell_parent.length; i++) {
                if (QS_city_spell_parent[i].split(',')) {
                  var city1Array = QS_city_spell_parent[i].split(',');
                  if (QS_city_spell[city1Array[0]]) {
                    if (QS_city_spell[city1Array[0]].split('`')) {
                      var city11Array = QS_city_spell[city1Array[0]].split('`');
                      htmlCity += '<ul class="list_nav J_list_city_group">';
                      htmlCity += [
                        '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '" data-title="' + city1Array[1] + '">',
                        '<label>不限</label>',
                        '</li>'
                      ].join('');
                      for (var j = 0; j < city11Array.length; j++) {
                        if (city11Array[j].split(',')) {
                          var jArray = city11Array[j].split(',');
                          htmlCity += [
                            '<li class="J_list_city" data-code="' + jArray[0] + '" data-title="' + jArray[1] + '">',
                            '<label>' + jArray[1] + '</label>',
                            '</li>'
                          ].join('');
                        };
                      };
                      htmlCity += '</ul>';
                    }
                  } else {
                    htmlCity += '<ul class="list_nav J_list_city_group">';
                    htmlCity += [
                      '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '" data-title="' + city1Array[1] + '">',
                      '<label>不限</label>',
                      '</li>'
                    ].join('');
                    htmlCity += '</ul>';
                  }
                };
              }
            };
            htmlCity += '</div>';
            htmlCity += '<div class="clear"></div>';
            htmlCity += '</div>';
          }
        } else { // 分站为一级
          if (QS_city_spell_parent) {
            htmlCity += '<div class="modal_body_box modal_body_box_site">';
            htmlCity += '<ul class="list_nav">';
            for (var i = 0; i < QS_city_spell_parent.length; i++) {
              if (QS_city_spell_parent[i].split(',')) {
                var iArray = QS_city_spell_parent[i].split(',');
                htmlCity += [
                  '<li class="J_list_city_parent" data-code="' + iArray[0] + '" data-title="' + iArray[1] + '">',
                  '<label>' + iArray[1] + '</label>',
                  '</li>'
                ].join('');
              };
            };
            htmlCity += '</ul>';
            htmlCity += '</div>';
          }
        }
      } else {
        if (!subsiteLevel1) { // 分站是二级
          if (QS_city_parent) {
            htmlCity += '<div class="modal_body_box modal_body_box3">';
            htmlCity += '<div class="left_box">';
            htmlCity += '<ul class="list_nav">';
            for (var i = 0; i < QS_city_parent.length; i++) {
              if (QS_city_parent[i].split(',')) {
                var iArray = QS_city_parent[i].split(',');
                htmlCity += [
                  '<li class="J_list_city_parent" data-code="' + iArray[0] + '" data-title="' + iArray[1] + '">',
                  '<label>' + iArray[1] + '</label>',
                  '</li>'
                ].join('');
              };
            };
            htmlCity += '</ul>';
            htmlCity += '</div>';
            htmlCity += '<div class="right_box">';
            if (QS_city_parent) {
              for (var i = 0; i < QS_city_parent.length; i++) {
                if (QS_city_parent[i].split(',')) {
                  var city1Array = QS_city_parent[i].split(',');
                  if (QS_city[city1Array[0]]) {
                    if (QS_city[city1Array[0]].split('`')) {
                      var city11Array = QS_city[city1Array[0]].split('`');
                      htmlCity += '<ul class="list_nav J_list_city_group">';
                      htmlCity += [
                        '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '" data-title="' + city1Array[1] + '">',
                        '<label>不限</label>',
                        '</li>'
                      ].join('');
                      for (var j = 0; j < city11Array.length; j++) {
                        if (city11Array[j].split(',')) {
                          var jArray = city11Array[j].split(',');
                          htmlCity += [
                            '<li class="J_list_city" data-code="' + jArray[0] + '" data-title="' + jArray[1] + '">',
                            '<label>' + jArray[1] + '</label>',
                            '</li>'
                          ].join('');
                        };
                      };
                      htmlCity += '</ul>';
                    }
                  } else {
                    htmlCity += '<ul class="list_nav J_list_city_group">';
                    htmlCity += [
                      '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '.0.0" data-title="' + city1Array[1] + '">',
                      '<label>不限</label>',
                      '</li>'
                    ].join('');
                    htmlCity += '</ul>';
                  }
                };
              }
            };
            htmlCity += '</div>';
            htmlCity += '<div class="clear"></div>';
            htmlCity += '</div>';
          }
        } else { // 分站为一级
          if (QS_city_parent) {
            htmlCity += '<div class="modal_body_box modal_body_box_site">';
            htmlCity += '<ul class="list_nav">';
            for (var i = 0; i < QS_city_parent.length; i++) {
              if (QS_city_parent[i].split(',')) {
                var iArray = QS_city_parent[i].split(',');
                htmlCity += [
                  '<li class="J_list_city_parent" data-code="' + iArray[0] + '" data-title="' + iArray[1] + '">',
                  '<label>' + iArray[1] + '</label>',
                  '</li>'
                ].join('');
              };
            };
            htmlCity += '</ul>';
            htmlCity += '</div>';
          }
        }
      }
    } else {
      if (isSpell) { // 拼音
        if (QS_city_spell_parent) {
          htmlCity += '<div class="modal_body_box modal_body_box3">';
          htmlCity += '<div class="left_box">';
          htmlCity += '<ul class="list_nav">';
          for (var i = 0; i < QS_city_spell_parent.length; i++) {
            if (QS_city_spell_parent[i].split(',')) {
              var iArray = QS_city_spell_parent[i].split(',');
              htmlCity += [
                '<li class="J_list_city_parent" data-code="' + iArray[0] + '" data-title="' + iArray[1] + '">',
                '<label>' + iArray[1] + '</label>',
                '</li>'
              ].join('');
            };
          };
          htmlCity += '</ul>';
          htmlCity += '</div>';
          htmlCity += '<div class="right_box">';
          if (QS_city_spell_parent) {
            for (var i = 0; i < QS_city_spell_parent.length; i++) {
              if (QS_city_spell_parent[i].split(',')) {
                var city1Array = QS_city_spell_parent[i].split(',');
                if (QS_city_spell[city1Array[0]]) {
                  if (QS_city_spell[city1Array[0]].split('`')) {
                    var city11Array = QS_city_spell[city1Array[0]].split('`');
                    htmlCity += '<ul class="list_nav J_list_city_group">';
                    htmlCity += [
                      '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '" data-title="' + city1Array[1] + '">',
                      '<label>不限</label>',
                      '</li>'
                    ].join('');
                    for (var j = 0; j < city11Array.length; j++) {
                      if (city11Array[j].split(',')) {
                        var jArray = city11Array[j].split(',');
                        htmlCity += [
                          '<li class="J_list_city" data-code="' + jArray[0] + '" data-title="' + jArray[1] + '">',
                          '<label>' + jArray[1] + '</label>',
                          '</li>'
                        ].join('');
                      };
                    };
                    htmlCity += '</ul>';
                  }
                } else {
                  htmlCity += '<ul class="list_nav J_list_city_group">';
                  htmlCity += [
                    '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '" data-title="' + city1Array[1] + '">',
                    '<label>不限</label>',
                    '</li>'
                  ].join('');
                  htmlCity += '</ul>';
                }
              };
            }
          };
          htmlCity += '</div>';
          htmlCity += '<div class="clear"></div>';
          htmlCity += '</div>';
        }
      } else {
        if (QS_city_parent) {
          htmlCity += '<div class="modal_body_box modal_body_box3">';
          htmlCity += '<div class="left_box">';
          htmlCity += '<ul class="list_nav">';
          for (var i = 0; i < QS_city_parent.length; i++) {
            if (QS_city_parent[i].split(',')) {
              var iArray = QS_city_parent[i].split(',');
              htmlCity += [
                '<li class="J_list_city_parent" data-code="' + iArray[0] + '" data-title="' + iArray[1] + '">',
                '<label>' + iArray[1] + '</label>',
                '</li>'
              ].join('');
            };
          };
          htmlCity += '</ul>';
          htmlCity += '</div>';
          htmlCity += '<div class="right_box">';
          if (QS_city_parent) {
            for (var i = 0; i < QS_city_parent.length; i++) {
              if (QS_city_parent[i].split(',')) {
                var city1Array = QS_city_parent[i].split(',');
                if (QS_city[city1Array[0]]) {
                  if (QS_city[city1Array[0]].split('`')) {
                    var city11Array = QS_city[city1Array[0]].split('`');
                    htmlCity += '<ul class="list_nav J_list_city_group">';
                    htmlCity += [
                      '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '.0.0" data-title="' + city1Array[1] + '">',
                      '<label>不限</label>',
                      '</li>'
                    ].join('');
                    for (var j = 0; j < city11Array.length; j++) {
                      if (city11Array[j].split(',')) {
                        var jArray = city11Array[j].split(',');
                        htmlCity += [
                          '<li class="J_list_city" data-code="' + city1Array[0] + '.' + jArray[0] + '.0" data-title="' + jArray[1] + '">',
                          '<label>' + jArray[1] + '</label>',
                          '</li>'
                        ].join('');
                      };
                    };
                    htmlCity += '</ul>';
                  }
                } else {
                  htmlCity += '<ul class="list_nav J_list_city_group">';
                  htmlCity += [
                    '<li class="J_list_city J_list_city_nolimit" data-code="' + city1Array[0] + '.0.0" data-title="' + city1Array[1] + '">',
                    '<label>不限</label>',
                    '</li>'
                  ].join('');
                  htmlCity += '</ul>';
                }
              };
            }
          };
          htmlCity += '</div>';
          htmlCity += '<div class="clear"></div>';
          htmlCity += '</div>';
        }
      }
    }

		prepareModal(titleValue, multipleValue, maxNumValue);

		$('.J_modal_content').html(htmlCity);
	    $('.J_btnyes').attr('id', 'J_btnyes_city');
	    $('.J_modal_content .right_box .list_nav').eq(0).show();
	    $('.J_list_city_parent').eq(0).addClass('current');
	    var msWidthValue = widthValue;
      if (subsiteLevel1) { // 一级分站宽度
        msWidthValue = subsiteLevel1Width;
      }
	    $('.modal_dialog').css({
			  width: msWidthValue + 'px',
	    	left: ($(window).width() - msWidthValue)/2,
	    	top: ($(window).height() - $('.modal_dialog').outerHeight())/2 + $(document).scrollTop()
	    });

	    $('.modal_backdrop').addClass('in');

	    // 恢复选中
    	var recoverValue = $('#recoverSearchCityModalCode').val();
    	if (recoverValue.length) {
		    if (multipleValue) {
		    	var recoverValueArray = recoverValue.split(',');
		    	if (subsiteLevel1) { // 一级分站
            for (var i = 0; i < recoverValueArray.length; i++) {
              $('.J_list_city_parent').each(function(index, el) {
                if ($(this).data('code') == recoverValueArray[i]) {
                  $(this).addClass('current');
                };
              });
            };
          } else {
            for (var i = 0; i < recoverValueArray.length; i++) {
              $('.J_list_city').each(function(index, el) {
                if ($(this).data('code') == recoverValueArray[i]) {
                  $(this).addClass('seledted');
                };
              });
            };
          }
          copyCitySelectedSecond();
		    } else {
		      if (subsiteLevel1) { // 一级分站
            $('.J_list_city_parent').removeClass('seledted current');
            $('.J_list_city_parent').each(function(index, el) {
              if ($(this).data('code') == recoverValue) {
                $(this).addClass('current');
              };
            });
          } else {
            $('.J_list_city').each(function(index, el) {
              if ($(this).data('code') == recoverValue) {
                $(this).addClass('seledted');
              };
            });
          }
		    }
		    // 不是一级分站才需要进行下一步
		    if (!subsiteLevel1) {
          $('.J_list_city_parent').removeClass('seledted current');
          var subscriptValue = 0;
          $('.J_list_city.seledted').each(function(index, el) {
            var thisGroup = $(this).closest('.J_list_city_group');
            subscriptValue = $('.J_list_city_group').index(thisGroup);
            $('.J_list_city_parent').eq(subscriptValue).addClass('seledted');
          });
          $('.J_list_city_group').eq(subscriptValue).show().siblings('.J_list_city_group').hide();
        }
	    }

	    // 一级地区点击
	    $('.J_list_city_parent').live('click', function() {
	    	$(this).addClass('current').siblings('.J_list_city_parent').removeClass('current');
	    	var subscriptValue = $('.J_list_city_parent').index(this);
	    	$('.J_list_city_group').eq(subscriptValue).show().siblings('.J_list_city_group').hide();
	    });

	    // 不限
	    $('.J_list_city_nolimit').die().live('click', function() {
	    	var thisGroup = $(this).closest('.J_list_city_group');
	    	thisGroup.find('.J_list_city').not('.J_list_city_nolimit').removeClass('seledted');
	    });

      // 二级地区点击
	    $('.J_list_city').die().live('click', function() {
	    	if ($(this).hasClass('seledted')) {
	    		$(this).removeClass('seledted');
	    		if (multipleValue) {
	    			copyCitySelectedSecond();
	    		};
	    		var thisGroup = $(this).closest('.J_list_city_group');
	    		var subscriptValue = $('.J_list_city_group').index(thisGroup);
	    		if (!thisGroup.find('.seledted').length) {
	    			$('.J_list_city_parent').eq(subscriptValue).removeClass('seledted').addClass('current');
	    		};
	    	} else {
	    		$(this).addClass('seledted');
	    		if (multipleValue) {
	    			if (!$(this).is('.J_list_city_nolimit')) {
    					var thisGroup = $(this).closest('.J_list_city_group');
    					thisGroup.find('.J_list_city_nolimit').removeClass('seledted');
    				};
	    			if ($('.J_list_city.seledted').length > maxNumValue) {
	    				$(this).removeClass('seledted');
	    				disapperTooltip("remind", '最多选择'+ maxNumValue +'个');
	    				return false;
	    			} else {
	    				copyCitySelectedSecond();
	    			}
	    			var thisGroup = $(this).closest('.J_list_city_group');
		    		var subscriptValue = $('.J_list_city_group').index(thisGroup);
		    		$('.J_list_city_parent').eq(subscriptValue).addClass('seledted');
	    		} else {
	    			var code = $(this).data('code');
            var title = $(this).data('title');
            $('#searchCityModalCode').val(code);
            $('#recoverSearchCityModalCode').val(code);
            $('#showSearchModal').text(title);
            $('#showSearchModal').attr('title', title);
            $('.modal_backdrop').remove();
            $('.modal').remove();
	    		}
	    	}
	    });

	    function copyCitySelectedSecond() {
	    	var htmlListed = '';
	    	$('.J_list_city.seledted').each(function(index, el) {
	    		var listedCode = $(this).data('code');
	    		var listedTitle = $(this).data('title');
	    		htmlListed += [
					'<div class="listed_item_parent J_listed_city" data-code="' + listedCode + '" data-title="' + listedTitle + '">',
						'<a href="javascript:;" class="listed_item">',
							'<span>' + listedTitle + '</span><div class="del"></div>',
						'</a>',
					'</div>'
				].join('');
	    	});
	    	$('#J_listed_content').html(htmlListed);
	    	$('#J_listed_group').show();
	    }

	    $('.J_listed_city').die().live('click', function() {
	    	var listedValue = $(this).data('code');
	    	$('.J_list_city').each(function(index, el) {
				if ($(this).data('code') == listedValue) {
					$(this).removeClass('seledted');
					var thisGroup = $(this).closest('.J_list_city_group');
		    		var subscriptValue = $('.J_list_city_group').index(thisGroup);
		    		if (!thisGroup.find('.seledted').length) {
		    			$('.J_list_city_parent').eq(subscriptValue).removeClass('seledted');
		    		};
				};
			});
			copyCitySelectedSecond();
	    });

	    $('#J_listed_clear').live('click', function() {
	    	$('.J_list_city.seledted').each(function(index, el) {
				$(this).removeClass('seledted');
			});
			$('.J_list_city_parent').removeClass('seledted');
			copyCitySelectedSecond();
	    });

	    // 确定
	    $('#J_btnyes_city').die().live('click', function() {
        var checkedArray = new Array();
	      if (isSubsite && subsiteLevel1) { // 是分站并且是一级分站
          checkedArray = $('.J_list_city_parent.current');
        } else {
          checkedArray = $('.J_list_city.seledted');
        }
        var codeArray = new Array();
        var titleArray = new Array();
        $.each(checkedArray, function(index, val) {
          codeArray[index] = $(this).data('code');
          titleArray[index] = $(this).data('title');
        });
        $('#searchCityModalCode').val(codeArray.join(','));
        $('#recoverSearchCityModalCode').val(codeArray.join(','));
        $('#showSearchModal').text(titleArray.length ? titleArray.join('+') : '请选择');
        $('#showSearchModal').attr('title', titleArray.length ? titleArray.join('+') : '请选择');
        removeModal();
	    });
	});

	function prepareModal(titleValue, multipleValue, maxNumValue) {
		var ie = !-[1,];
		var ie6 = !-[1,]&&!window.XMLHttpRequest;
		$(backdropLayerTpl).appendTo($(document.body));
		if (ie6) {
			$('.modal_backdrop').css("height", $(document).height());
		}
		$(htmlLayerTpl).appendTo($(document.body));

		$('.J_modal_title').text(titleValue);
		if (multipleValue) {
	    	$('.J_modal_max').text('（最多选择'+ maxNumValue +'个）');
	    };

		$(".J_hoverbut").hover(
			function() {
				$(this).addClass("hover");
			},
			function() {
				$(this).removeClass("hover");
			}
		);

		// 可拖动
		var newObj = $('.modal_dialog');
		var newTit = newObj.find(".modal_header");

		newTit.mousedown(function(e) {
			var offset = newObj.offset();
			var x = e.pageX - offset.left;
            var y = e.pageY - offset.top;
            $(document).bind('mousemove', function(ev) {
            	newObj.bind('selectstart', function() {
                    return false;
                });
                var newx = ev.pageX - x;
                var newy = ev.pageY - y;
                newObj.css({
                    'left': newx + "px",
                    'top': newy + "px"
                });
            });
		});

		$(document).mouseup(function() {
            $(this).unbind("mousemove");
        })

		if (ie) {
			if (window.PIE) { 
	            $('.pie_about').each(function() {
	                PIE.attach(this);
	            });
	        }
		};
	}

	$('.J_dismiss_modal').live('click', function() {
        removeModal();
    });

    $(document).on('keydown', function(event) {
 		if (event.keyCode == 27) {
			removeModal();
		}
 	});

	function removeModal() {
		setTimeout(function() { 
	    	$('.modal_backdrop').remove();
 			$('.modal').remove();
		},50)
	}
	
}(window.jQuery);