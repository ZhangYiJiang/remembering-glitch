// Load memorial messages
var memories = $('#memories'), 
	margin = 20, postPadding = 15*2, targetColWidth = 170 + postPadding,
	cols = Math.round( memories.width() / targetColWidth), 
	colWidth = Math.floor(memories.width() / cols), 
	colors = ['#FE615B', '#FEA26E', '#FF7300', '#B9BF04', 
				'#1485CC', '#8D5DDE', '#D97925', '#D97925', '#002635', 
				'#AEC844', '#6DD0FF', '#452743', '#F2C500', '#578091', 
				'#A3E2ED', '#93BA43', '#B8503B'],
	anchors;

var dataUrl = '/memorial/view/load/';

$(document).on('fontfaceapplied', getMemories);

function getMemories () {
	memories.empty();

	memories.masonry({
		itemSelector: 'a',
		columnWidth: colWidth, 
		containerStyle: {
			position: 'fixed'
		}
	});

	$.getJSON(dataUrl, processMemories);
}

function processMemories (data) {
	data.forEach(function(d){
		renderItem(d);
	});

	memories.masonry('reload');

	// Remove the ones that cannot be seen 
	memories.children().each(function(){
		if ($(this).offset().top > $(window).height()) {
			$(this).remove();
		}
	});

	// Shuffle and make the rest appear one by one
	anchors = memories.children().hide().get();
	fisherYates(anchors);
	anchors.forEach(function(ele, i){
		$(ele).delay( i * 600 ).fadeIn(500);
	});
}


function renderItem (data) {
	var colspan = Math.ceil(Math.random() * 2);	

	// Scale font size with number of characters
	var fontSize = Math.round(20 + ((120 - data.post.length) / 120) * 15 * colspan);

	// Random number of columns 
	var width = colspan * colWidth - postPadding;

	var a = $('<a>', {
		html: data.post, 
		href: '/memorial/' + data.id, 
		css: {
			color: colors[Math.floor(colors.length * Math.random())], 
			fontSize: fontSize, 
			width: width
		}
	}).appendTo(memories);

	// Resize font size 
	var maxAddition = 20, i = 0, lastHeight = a.height();
	while (i < maxAddition) {
		a.css('font-size', '+=1');

		if ((a.height() - lastHeight) > fontSize) {
			a.css('font-size', '-=1');
			break;
		}

		lastHeight = a.height();
		i++;
	}

	if (fontSize + i > 55) {
		a.css('line-height', '1em');
	} else if (fontSize + i > 40) {
		a.css('line-height', '1.1em');
	}

	return a;
}

// Refresh button 
$('#refresh').on('click', function(){
	anchors.forEach(function(ele, i){
		$(ele).delay(i*150).fadeOut(200);
	});

	setTimeout(getMemories, anchors.length * 150);

	return false;
});

// Hide #remember button 
var remember = $('#remember');

$('#hide-remember').click(function(){
	var margin = parseInt(remember.css('margin-left'), 10) == -500 ? 
		($(window).width() - remember.width()) / 2 : -500;

	remember.toggleClass('hidden').animate({
		marginLeft: margin
	}, 1000);

	return false;
});

// Character count
var tweetTextarea = $('#add-tweet textarea');

tweetTextarea.on('input', function(){
	var charLeft = tweetTextarea.attr('maxlength') - tweetTextarea.val().length;
	$('#character-count').text(charLeft + ' characters left');
}).trigger('input');

// Prose switch
$('#prose-switch').click(function(){
	$('#add-tweet').slideUp();
	$('#add-prose').slideDown();

	var proseArea = $('#add-prose textarea');
	proseArea.val(proseArea.val() + tweetTextarea.val());

	return false;
});

// Add snap thingy 
$('#add-snap button').click(function(){
	var btn = $(this).prop('disabled', true);

	$.post('/memorial/add/snap', {
		'snap': $('#snap_url').val(), 
		'post': $('#post_id').val()
	}, function(data) {
		if (data == 'Success') {
			window.location.reload();
		} else {
			alert(data + '. Please try again later.');
			btn.prop('disabled', false);
		}
	});

	return false;
});

// Shuffle util function 
function fisherYates ( myArray ) {
	var i = myArray.length;
	if ( i == 0 ) return false;
	while ( --i ) {
		var j = Math.floor( Math.random() * ( i + 1 ) );
		var tempi = myArray[i];
		var tempj = myArray[j];
		myArray[i] = tempj;
		myArray[j] = tempi;
	}
}

// Listen for document.readystate === 'complete' to ensure font-face is loaded
// see http://stackoverflow.com/q/6677181/313758

document.onreadystatechange = function() {
	if (document.readyState === 'complete') 
		$(document).trigger('fontfaceapplied');
};