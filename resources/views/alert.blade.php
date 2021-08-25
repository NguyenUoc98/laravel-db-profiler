<style>
    .collapsible {
        background-color: #fff;
        color: red;
        padding: 10px;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        border-top: 2px solid red;
        width: fit-content;
        display: flex;
        justify-content: space-between;
    }

    .btn-db-profiler {
        background-color: #fff;
        cursor: pointer;
        border: none;
    }

    .btn-db-profiler:focus {
        outline: none;
    }

    .content {
        padding: 10px 10px;
        display: none;
        background-color: #f1f1f1;
        height: 400px;
    }
</style>
<div class="collapsible" id="db-profiler-collapsible">
    <span>Database Profiler</span>
    <div>
        <button type="button" id="db-profiler-open" class="btn-db-profiler">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11 3C10.4477 3 10 3.44772 10 4C10 4.55228 10.4477 5 11 5H13.5858L7.29289 11.2929C6.90237 11.6834 6.90237 12.3166 7.29289 12.7071C7.68342 13.0976 8.31658 13.0976 8.70711 12.7071L15 6.41421V9C15 9.55228 15.4477 10 16 10C16.5523 10 17 9.55228 17 9V4C17 3.44772 16.5523 3 16 3H11Z"
                    fill="#EB5757"/>
                <path
                    d="M5 5C3.89543 5 3 5.89543 3 7V15C3 16.1046 3.89543 17 5 17H13C14.1046 17 15 16.1046 15 15V12C15 11.4477 14.5523 11 14 11C13.4477 11 13 11.4477 13 12V15H5V7L8 7C8.55228 7 9 6.55228 9 6C9 5.44772 8.55228 5 8 5H5Z"
                    fill="#EB5757"/>
            </svg>
        </button>
        <button type="button" class="btn-db-profiler" id="db-profiler-reload" style="display:none">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M4 2C4.55228 2 5 2.44772 5 3V5.10125C6.27009 3.80489 8.04052 3 10 3C13.0494 3 15.641 4.94932 16.6014 7.66675C16.7855 8.18747 16.5126 8.75879 15.9918 8.94284C15.4711 9.12689 14.8998 8.85396 14.7157 8.33325C14.0289 6.38991 12.1755 5 10 5C8.36507 5 6.91204 5.78502 5.99935 7H9C9.55228 7 10 7.44772 10 8C10 8.55228 9.55228 9 9 9H4C3.44772 9 3 8.55228 3 8V3C3 2.44772 3.44772 2 4 2ZM4.00817 11.0572C4.52888 10.8731 5.1002 11.146 5.28425 11.6668C5.97112 13.6101 7.82453 15 10 15C11.6349 15 13.088 14.215 14.0006 13L11 13C10.4477 13 10 12.5523 10 12C10 11.4477 10.4477 11 11 11H16C16.2652 11 16.5196 11.1054 16.7071 11.2929C16.8946 11.4804 17 11.7348 17 12V17C17 17.5523 16.5523 18 16 18C15.4477 18 15 17.5523 15 17V14.8987C13.7299 16.1951 11.9595 17 10 17C6.95059 17 4.35905 15.0507 3.39857 12.3332C3.21452 11.8125 3.48745 11.2412 4.00817 11.0572Z"
                      fill="#EB5757"/>
            </svg>
        </button>
        <button type="button" class="btn-db-profiler" id="db-profiler-close" style="display:none">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L10 8.58579L14.2929 4.29289C14.6834 3.90237 15.3166 3.90237 15.7071 4.29289C16.0976 4.68342 16.0976 5.31658 15.7071 5.70711L11.4142 10L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L10 11.4142L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L8.58579 10L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z"
                      fill="#EB5757"/>
            </svg>
        </button>
    </div>
</div>

<div class="content" id="db-profiler-content">
</div>
<script>
    var dbProfilerColl = document.getElementById("db-profiler-collapsible");
    var dbProfilerOpen = document.getElementById('db-profiler-open');
    var dbProfilerClose = document.getElementById('db-profiler-close');
    var dbProfilerReload = document.getElementById('db-profiler-reload');
    var content = dbProfilerColl.nextElementSibling;

    dbProfilerOpen.addEventListener("click", function () {
        dbProfilerColl.style.width = "100%";
        dbProfilerColl.classList.toggle("active");
        var content = dbProfilerColl.nextElementSibling;
        content.style.display = "block";
        this.style.display = "none";
        dbProfilerClose.style.display = "inline-block";
        dbProfilerReload.style.display = "inline-block";
    });

    dbProfilerClose.addEventListener("click", function () {
        content.style.display = "none";
        dbProfilerColl.style.width = "fit-content";
        this.style.display = "none";
        dbProfilerReload.style.display = "none";
        dbProfilerOpen.style.display = "block";
    });

    dbProfilerReload.addEventListener("click", function () {
        document.getElementById('db-profiler-iframe').contentWindow.location.reload();
    });

    window.onload = function () {
        content.innerHTML = '<iframe id="db-profiler-iframe" src="{{config('app.url') . '?vvvv'}}" title="Database Profiler" style="width: 100%; height: 100%; border:none"></iframe>';
    };
</script>
