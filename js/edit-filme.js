document.getElementById("done-movie-title-bt").addEventListener("click", runAjax);
document.getElementById("delete-movie-bt").addEventListener("click", deleteAjax);
document.getElementById("cover-img").addEventListener("click", () => $("#cover-src").attr("hidden", false));

const searchParams = new URLSearchParams(window.location.search)

function runAjax() {
    $("#done-movie-title-bt i").html("cloud_upload");
    const idMovie = Number(searchParams.get('f'));
    const titleText = $("#title-input").text();
    const urlMatch = $("#video-url").text().match(/(?<=v=).+($|&)/i);
    const directorName = $("#director-name").text();
    const coverUrl = $("#cover-src").text();
    $("#cover-img").attr("src", coverUrl);
    if(!urlMatch){
        alert("Sua url do filme precisa conter o código v=código do filme");
        
    } else {
        $("iframe#movie-frame").attr("src", `https://www.youtube.com/embed/${urlMatch[0]}`)
    }

    $.post(
        "api/update-movie.php",
        {
            idMovie,
            titleText,
            movieSource: urlMatch[0],
            directorName,
            coverUrl
        },
        function (response) {
            $("#done-movie-title-bt i").html("cloud_done");
        })
        .fail(function () {
            $("#done-movie-title-bt i").html("error");
        })
    return false;
}

function deleteAjax() {
    $("#delete-movie-bt i").html("cloud_upload");
    const idMovie = Number(searchParams.get('f'));

    $.post(
        "api/delete-movie.php",
        {
            idMovie,
        },
        function (response) {
            $("#delete-movie-bt i").html("cloud_done");
            window.location.href = "../BD-FILME";
        })
        .fail(function () {
            $("#delete-movie-bt i").html("error");
        })
    return false;
}
