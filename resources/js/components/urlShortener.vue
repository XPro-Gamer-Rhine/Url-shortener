<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div v-if="AuthorizedUser">
                    <div class="card">
                        <div class="card-header">Simple URL Shortener</div>
                        <div class="card-body">
                            <form
                                action=""
                                class="form"
                                method=""
                                enctype="multipart/form-data"
                            >
                                <div class="input-form">
                                    <label for="url">URL</label>
                                    <input
                                        type="text"
                                        name="url"
                                        v-model="url"
                                        id="url"
                                        class="form-control"
                                        placeholder="Enter URL"
                                    />
                                </div>
                                <button
                                    class="btn btn-primary"
                                    style="margin-top: 8px"
                                    v-on:click.prevent="shortenURL"
                                >
                                    Shorten
                                </button>
                            </form>
                            <p v-if="!urlNotFound" class="alert alert-danger">
                                URL is not valid
                            </p>
                            <div class="copyLink mb-5">
                                <span id="output_url"> </span>
                                <span
                                    id="copyToClipBoard"
                                    v-on:click.prevent="copyURL"
                                    class="button-1"
                                >
                                    {{ copyUrlString }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="card">
                        <div class="card-header" style="color: red">
                            User is not authorized.
                        </div>
                        <div class="card-body">
                            <p>
                                <a href="/login" class="btn btn-primary"
                                    >Login</a
                                >
                                OR
                                <a href="/register" class="btn btn-info"
                                    >Register</a
                                >
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { $dataMetaSchema } from "ajv";
import axios from "axios";

export default {
    mounted() {
        console.log("Component mounted.");
    },
    props: ["AuthorizedUser"],
    data() {
        return {
            url: null,
            urlNotFound: true,
            copyUrlString: "Copy URL To Clipboard",
            response: null,
        };
    },
    methods: {
        shortenURL() {
            let self = this;
            let newURL = self.url;
            let newArray = newURL.split("//");
            let counter = 0;

            let resultNewUrl = Math.round(
                Math.pow(36, 8) - Math.random() * Math.pow(36, 8)
            )
                .toString(36)
                .slice(1);
            for (let i = 0; i < newArray.length; i++) {
                if (newArray[i] == "http:" || newArray[i] == "https:") {
                    counter++;
                }
                if (counter == 0) {
                    let newArrayOne = newURL.split(".");
                    if (newArrayOne[i] == "www") {
                        counter++;
                    }

                    let newArrayTwo = newURL.indexOf(".com");
                    if (newArrayTwo >= 0) {
                        counter++;
                    }

                    let newArrayThree = newURL.indexOf(".net");
                    if (newArrayThree >= 0) {
                        counter++;
                    }

                    let newArrayFour = newURL.indexOf(".org");
                    if (newArrayFour >= 0) {
                        counter++;
                    }

                    let newArrayFive = newURL.indexOf(".app");
                    if (newArrayFive >= 0) {
                        counter++;
                    }
                }

                if (counter == 0) {
                    self.urlNotFound = true;
                    return;
                } else {
                    let currentURL = window.location.href + resultNewUrl;
                    axios
                        .post("/url/shorten", {
                            url: newURL,
                            shortUrl: currentURL,
                        })
                        .then(function (response) {
                            self.response = response.data;
                            $(".copyLink").fadeIn(500);
                            self.url = self.response;
                            console.log(response);
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            }
        },
        copyURL() {
            let id = $("#url").select();
            this.copyUrlString = "Url Copied Successfully";
            document.execCommand("copy");
            this.url = this.response;
        },
    },
};
</script>
<style scoped>
.copyLink {
    display: block;
}
#copyToClipBoard {
    display: block;
    margin-top: 28px;
    font-size: 17px;
}
#copyToClipBoard:hover {
    cursor: pointer;
}

#copyToClipBoard:visited,
#copyToClipBoard:active,
#copyToClipBoard:focus {
    background-color: rgba(1, 124, 62, 0);
    color: rgb(0, 0, 0);
}

.button-1 {
    background-color: #ea4c89;
    border-radius: 8px;
    border-style: none;
    box-sizing: border-box;
    color: #ffffff;
    cursor: pointer;
    display: inline-block;
    font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial,
        sans-serif;
    font-size: 14px;
    font-weight: 500;
    height: 40px;
    line-height: 20px;
    list-style: none;
    margin: 0;
    outline: none;
    padding: 10px 16px;
    position: relative;
    text-align: center;
    text-decoration: none;
    transition: color 100ms;
    vertical-align: baseline;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
}

.button-1:hover,
.button-1:focus {
    background-color: #f082ac;
}
</style>
