
var audio = $('#player');
var end_playback = false;

function change_track(song_id)
{		
	$('body').find('.info').removeClass('info');
	$('[data-key='+song_id+']').addClass('info');
	audio[0].pause();
	$("#mp3_src").attr("src", $('[data-key='+song_id+']').attr('data-location'));
	audio[0].load();
	audio[0].play();
	end_playback = false;
}

	
$('body').on('click', '.glyphicon-play', function(event){
	change_track($(this).parents('tr').attr('data-key'));
	event.preventDefault();
});

audio.on("ended",function(){
	$(document).load("index.php?r=song/song-played&song_id="+$('.info').attr('data-key')+"", function(data)
	{
		console.log(data);
	});

	//var temp_count = parseInt($('.info td:eq(2)').html());
		
	var date = new Date();
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getSeconds();	

	if(hours < 10)
	{
		hours = "0"+hours;
	}

	if(minutes < 10)
	{
		minutes = "0"+minutes;
	}

	if(seconds < 10)
	{
		seconds = "0"+seconds;
	}

	var time = "today " + hours + ":" + minutes + ":" + seconds;

	//$("#playlist_body [song_id="+current_song_id+"]").find('#count_played').html(temp_count+1);
	var index = $('th:contains("Last Played")').index();
	if(index > 0)
	{
		$('.info td:eq('+index+')').html(time);
	}
			
	if($('.info').next("tr").attr('data-key') === undefined || end_playback == true)
	{
		console.log("Playlist ended");
		$('body').find('.info').removeClass('info');
	}
	else
	{
		change_track($('.info').next("tr").attr('data-key'));		
	}
});

$('body').on('click', '#playlist_button', function(event){
	var data1 = $('#w0').yiiGridView('getSelectedRows');
	var data2 = $('#songsearch-playlist_id').find("option:selected").attr('value');
	
	$.post({
		url: 'index.php?r=song/add-to-playlist',
		dataType: 'json',
		data: {
			song_id: data1,
			playlist_id : data2,
		},
		success: function(data){
			console.log('status: '+data.status);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.responseText);
		},
	});
	
});

$('body').on('click', '.index-random_remove', function(event){
	$(this).parents('tr').remove();
	event.preventDefault();
});

$('body').on('click', '#end_playback', function(event){
	end_playback = true;
	console.log("Playback will stop after current song...");
	event.preventDefault();
});

$('body').on('click', '#next', function(event){
	if($('.info').next("tr").attr('data-key') === undefined)
	{
		console.log("Invalid SongID!");
	}
	else
	{
		change_track($('.info').next("tr").attr('data-key'));		
	}
	event.preventDefault();
});

$('body').on('click', '.glyphicon-star', function(event){

	$(this).hide('fast');
	
	var data = $(this).parents('tr').attr('data-key');
	
	$.post({
		url: 'index.php?r=song/rate-up',
		dataType: 'json',
		data: {
			song_id: data,
		},
		success: function(data){
			console.log('status: '+data.status+' song_id: '+data.song_id);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.responseText);
		},
	});
	
	event.preventDefault();
	
});

$('body').on('click', '.glyphicon-star-empty', function(event){

	$(this).hide('fast');	
	
	var data = $(this).parents('tr').attr('data-key');
	var index = $('th:contains("Rating")').index();
	var element = this;
	
	$.post({
		url: 'index.php?r=song/rate-down',
		dataType: 'json',
		data: {
			song_id: data,
		},
		success: function(data){
			console.log('status: '+data.status+' song_id: '+data.song_id);
			if(index > 0)
			{
				$(element).parents('tr').find('td:eq('+index+')').html('down');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.responseText);
		},
	});
	
	event.preventDefault();
	
});
