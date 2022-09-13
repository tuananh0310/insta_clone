<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Story</title>
    <link rel="stylesheet" href="{{ asset('css/story.css') }}">
</head>
<body>
<div id="app">
    <div class="container">
        <div id="times" class="time">
        </div>
        <div class="content">
            <div class="texts">
                <h1 id="title"></h1>
                <h5 id="description"></h5>
            </div>
        </div>
        <div id="back"></div>
        <div id="next"></div>
        <a href="{{route('profile.index',$user->username)}}" >
            <span class="close" ><span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Code/Error-circle.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
        <path d="M12.0355339,10.6213203 L14.863961,7.79289322 C15.2544853,7.40236893 15.8876503,7.40236893 16.2781746,7.79289322 C16.6686989,8.18341751 16.6686989,8.81658249 16.2781746,9.20710678 L13.4497475,12.0355339 L16.2781746,14.863961 C16.6686989,15.2544853 16.6686989,15.8876503 16.2781746,16.2781746 C15.8876503,16.6686989 15.2544853,16.6686989 14.863961,16.2781746 L12.0355339,13.4497475 L9.20710678,16.2781746 C8.81658249,16.6686989 8.18341751,16.6686989 7.79289322,16.2781746 C7.40236893,15.8876503 7.40236893,15.2544853 7.79289322,14.863961 L10.6213203,12.0355339 L7.79289322,9.20710678 C7.40236893,8.81658249 7.40236893,8.18341751 7.79289322,7.79289322 C8.18341751,7.40236893 8.81658249,7.40236893 9.20710678,7.79289322 L12.0355339,10.6213203 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span></span>
        </a>
    </div>
</div>
<script>
    const stories = @json($stories);

    const container = document.querySelector(".container");
    const nextButton = document.querySelector("#next");
    const backButton = document.querySelector("#back");

    function Storyfier(storiesArray, rootEl) {
        this.stories = storiesArray;
        this.root = rootEl;
        this.times = rootEl.querySelector("#times");
        this.currentTime = 0;
        this.currentIndex = 0;

        // create breakpoints for when the slides should change
        this.intervals = this.stories.map((story, index) => {
            // TODO change so that it just uses the previous value + current time
            let sum = 0;
            for (let i = 0; i < index; i++) {
                sum += this.stories[i].time;
            }
            return sum;
        });
        // necessary to make sure the last slide plays to completion
        this.maxTime =
            this.intervals[this.intervals.length - 1] +
            this.stories[this.stories.length - 1].time;

        // render progress bars
        this.progressBars = this.stories.map(() => {
            const el = document.createElement("div");
            el.classList.add("time-item");
            el.innerHTML = '<div style="width: 0%"></div>';
            return el;
        });

        this.progressBars.forEach(el => {
            this.times.appendChild(el);
        });

        // methods
        this.render = () => {
            const story = this.stories[this.currentIndex];
            this.root.style.background = `url('${story.image}')`;
            this.root.querySelector("#title").innerHTML = story.title;
            this.root.querySelector("#description").innerHTML = story.description;
        };

        this.updateProgress = () => {
            this.progressBars.map((bar, index) => {
                // Fill already passed bars
                if (this.currentIndex > index) {
                    bar.querySelector("div").style.width = "100%";
                    return;
                }

                if (this.currentIndex < index) {
                    bar.querySelector("div").style.width = "0%";
                    return;
                }

                // update progress of current bar
                if (this.currentIndex == index) {
                    const timeStart = this.intervals[this.currentIndex];

                    let timeEnd;
                    if (this.currentIndex == this.stories.length - 1) {
                        timeEnd = this.maxTime;
                    } else {
                        timeEnd = this.intervals[this.currentIndex + 1];
                    }

                    const animatable = bar.querySelector("div");
                    animatable.style.width = `${((this.currentTime - timeStart) /
                        (timeEnd - timeStart)) *
                    100}%`;
                }
            });
        };
    }

    Storyfier.prototype.start = function() {
        // Render initial state
        this.render();

        // START INTERVAL
        const test = setInterval(() => {
            this.currentTime += 10;
            this.updateProgress();

            if (
                this.currentIndex >= this.stories.length - 1 &&
                this.currentTime > this.maxTime
            ) {
                clearInterval(test);
                return;
            }

            const lastIndex = this.currentIndex;
            if (this.currentTime >= this.intervals[this.currentIndex + 1]) {
                this.currentIndex += 1;
            }

            if (this.currentIndex != lastIndex) {
                this.render();
            }
        }, 10);
    };

    Storyfier.prototype.next = function() {
        const next = this.currentIndex + 1;
        if (next > this.stories.length - 1) {
            return;
        }

        this.currentIndex = next;
        this.currentTime = this.intervals[this.currentIndex];
        this.render();
    };

    Storyfier.prototype.back = function() {
        if (
            this.currentTime > this.intervals[this.currentIndex] + 35 ||
            this.currentIndex === 0
        ) {
            this.currentTime = this.intervals[this.currentIndex];
            return;
        }

        this.currentIndex -= 1;
        this.currentTime = this.intervals[this.currentIndex];
        this.render();
    };
    const setup = async () => {
        const loadImages = stories.map(({ image }) => {
            return new Promise((resolve, reject) => {
                let img = new Image();
                img.onload = () => {
                    resolve(image);
                };
                img.src = image;
            });
        });
        await Promise.all(loadImages);

        const s = new Storyfier(stories, container);
        s.start();

        nextButton.addEventListener("click", () => {
            s.next();
        });

        backButton.addEventListener("click", () => {
            s.back();
        });
    };
    setup();

</script>
</body>
</html>
