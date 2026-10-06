var prevMenuIdDir = 0;

$(document).ready(function() 
{
    //collapseTree('tree1');
    expandTree('tree1');
});

function showMenu(idDir)
{
    if (prevMenuIdDir != 0 && prevMenuIdDir != idDir)
        $('#menu-' + prevMenuIdDir).hide();
        
    $('#menu-' + idDir).show();
    prevMenuIdDir = idDir;
}

function hideMenu(idDir)
{
    $('#menu-' + idDir).hide();
}

function createfolder(idDir)
{
	newWindow('fileshare.dirs.create.php?iddir='+idDir,'BuatFolder','550','250','resizable=1,scrollbars=0,status=0,toolbar=0');
}

function delfolder(idDir)
{
    if (!confirm("Hapus folder ini beserta semua file di dalamnya? "))
        return;

    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "delfolder");
    qsb.add("iddir", idDir);

	$.ajax({
		url: "fileshare.dirs.ajax.php",
		method: "POST",
		data: qsb.createQs(),
		success: function(json)
		{
			var res = JSON.parse(json);
			if (parseInt(res[0]) < 0)
            {
                alert(res[1]);
                return;
            }
				
			document.location.reload();
		},
		error: function(xhr)
		{
			alert(xhr.responseText);
		},
		complete: function()
		{
			$("#dvLoading").hide();
		}
	});
}

function onNewFolder()
{
    document.location.reload();    
}