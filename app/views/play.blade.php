@extends('master.page')
@section('head')
{{HTML::script('js/game.js')}}
@stop
@section('content')
<script type="text/javascript">
    var username = "{{ $username }}";
</script>
<div id="body-container">
    <div id="output"></div>
    <div id="myDice"></div>
    <div id="diceAvailable"></div>
    <div id="moveHistory"></div>
    <div id="currentlyQueued">You are in the queue, please wait while we find you a game.</div>
    <div id="roundResult" class="white_content"><div class="exit">X</div><div class="content"></div></div>
    <div id="turnFormContainer">
        <div id="turnForm">
            <form id="turnFormRaise" action="/apifight/public/api/v1/game/move" type="post">
                Dice: <input type="text" name="dice_number"><br>
                Amount: <input type="text" name="amount"><br>
                <input type="hidden" name="call" value="raise" checked="checked">
                <input type="submit" value="Raise">
            </form>

            <form id="turnFormLie" action="/apifight/public/api/v1/game/move" type="post">
                <input type="hidden" name="call" value="lie">
                <input type="submit" value="Lie">
            </form>

            <form id="turnFormPerfect" action="/apifight/public/api/v1/game/move" type="post">
                <input type="hidden" name="call" value="perfect">
                <input type="submit" value="Spot on">
            </form>
        </div>
    </div>
</div>



<div id="diceSvg">
    <!-- Empty Dice -->
    <div id="emptyDice">
        <div class="dice float textContent">
            &nbsp;
        </div>
    </div>

    <!-- 1 -->
    <div id="dice1">
        <div class="dice float">
            <svg class="drawing" width="80" height="80" xmlns="http://www.w3.org/2000/svg">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <circle id="svg_1" r="9.8995" cy="40.5" cx="40.5" stroke-linecap="null" stroke-linejoin="null"
                            stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e"/>
                    <ellipse stroke="#000000" transform="rotate(-34.9393 39.5498 39.1399)" ry="7.94552" rx="9.59012" id="svg_3"
                             cy="39.13988" cx="39.54978" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                             stroke-width="0" fill="#353535"/>
                    <ellipse id="svg_4" cy="60.5" cx="98.5" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                             stroke-width="0" stroke="#000000" fill="#353535"/>
                    <ellipse id="svg_5" cy="40.25" cx="38.25" stroke-linecap="null" stroke-linejoin="null"
                             stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535"/>
                    <ellipse id="svg_6" cy="41.25" cx="28.75" stroke-linecap="null" stroke-linejoin="null"
                             stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535"/>
                </g>
            </svg>
        </div>
    </div>

    <!-- 2 -->
    <div id="dice2">
        <div  class="dice float">
            <svg width="80" height="80" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_7">
                        <circle r="9.8995" cy="18.92719" cx="64.01907" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_8"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 63.0689 17.5671)" ry="7.94552" rx="9.59012"
                                 cy="17.56707" cx="63.06885" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_9"/>
                        <ellipse cy="38.92719" cx="122.01907" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_10"/>
                        <ellipse cy="18.67719" cx="61.76907" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_11"/>
                        <ellipse cy="19.67719" cx="52.26907" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_12"/>
                    </g>
                    <g id="svg_13">
                        <circle r="9.8995" cy="61.67718" cx="17.26909" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_14"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 16.3189 60.3171)" ry="7.94552" rx="9.59012"
                                 cy="60.31706" cx="16.31887" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_15"/>
                        <ellipse cy="81.67718" cx="75.26909" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_16"/>
                        <ellipse cy="61.42718" cx="15.01909" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_17"/>
                        <ellipse cy="62.42718" cx="5.51909" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                                 stroke-width="0" stroke="#000000" fill="#353535" id="svg_18"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 3 -->
    <div id="dice3">
        <div class="dice float">
            <svg width="80" height="80" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_2">
                        <circle fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                stroke-linecap="null" cx="40.5" cy="40.5" r="9.8995" id="svg_1"/>
                        <ellipse fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="39.54978" cy="39.13988" id="svg_3" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 39.5498 39.1399)" stroke="#000000"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="98.5" cy="60.5" id="svg_4"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="38.25" cy="40.25" id="svg_5"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="28.75" cy="41.25" id="svg_6"/>
                    </g>
                    <g id="svg_7">
                        <circle id="svg_8" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="63.01907" cy="19.42719" r="9.8995"/>
                        <ellipse id="svg_9" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="62.06885" cy="18.06707" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 62.0689 18.0671)" stroke="#000000"/>
                        <ellipse id="svg_10" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="121.01907" cy="39.42719"/>
                        <ellipse id="svg_11" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="60.76907" cy="19.17719"/>
                        <ellipse id="svg_12" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="51.26907" cy="20.17719"/>
                    </g>
                    <g id="svg_13">
                        <circle id="svg_14" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="18.26909" cy="61.67718" r="9.8995"/>
                        <ellipse id="svg_15" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="17.31887" cy="60.31706" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 17.3189 60.3171)" stroke="#000000"/>
                        <ellipse id="svg_16" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="76.26909" cy="81.67718"/>
                        <ellipse id="svg_17" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="16.01909" cy="61.42718"/>
                        <ellipse id="svg_18" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="6.51909" cy="62.42718"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 4 -->
    <div id="dice4">
        <div class="dice float">
            <svg width="80" height="80" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_7">
                        <circle id="svg_8" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="64.01907" cy="18.92719" r="9.8995"/>
                        <ellipse id="svg_9" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="63.06885" cy="17.56707" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 63.0689 17.5671)" stroke="#000000"/>
                        <ellipse id="svg_10" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="122.01907" cy="38.92719"/>
                        <ellipse id="svg_11" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="61.76907" cy="18.67719"/>
                        <ellipse id="svg_12" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="52.26907" cy="19.67719"/>
                    </g>
                    <g id="svg_13">
                        <circle id="svg_14" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="17.26909" cy="61.67718" r="9.8995"/>
                        <ellipse id="svg_15" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="16.31887" cy="60.31706" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 16.3189 60.3171)" stroke="#000000"/>
                        <ellipse id="svg_16" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="75.26909" cy="81.67718"/>
                        <ellipse id="svg_17" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="15.01909" cy="61.42718"/>
                        <ellipse id="svg_18" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="5.51909" cy="62.42718"/>
                    </g>
                    <g id="svg_19">
                        <circle id="svg_20" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="17.15364" cy="18.76575" r="9.8995"/>
                        <ellipse id="svg_21" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="16.20342" cy="17.40563" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 16.2034 17.4057)" stroke="#000000"/>
                        <ellipse id="svg_22" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="75.15364" cy="38.76575"/>
                        <ellipse id="svg_23" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="14.90364" cy="18.51575"/>
                        <ellipse id="svg_24" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="5.40364" cy="19.51575"/>
                    </g>
                    <g id="svg_25">
                        <circle id="svg_26" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="63.15364" cy="61.76575" r="9.8995"/>
                        <ellipse id="svg_27" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="62.20342" cy="60.40563" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 62.2034 60.4057)" stroke="#000000"/>
                        <ellipse id="svg_28" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="121.15364" cy="81.76575"/>
                        <ellipse id="svg_29" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="60.90364" cy="61.51575"/>
                        <ellipse id="svg_30" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="51.40364" cy="62.51575"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 5 -->
    <div id="dice5">
        <div class="dice float">
            <svg width="80" height="80" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_2">
                        <circle fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                stroke-linecap="null" cx="40.5" cy="40.5" r="9.8995" id="svg_1"/>
                        <ellipse fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="39.54978" cy="39.13988" id="svg_3" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 39.5498 39.1399)" stroke="#000000"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="98.5" cy="60.5" id="svg_4"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="38.25" cy="40.25" id="svg_5"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="28.75" cy="41.25" id="svg_6"/>
                    </g>
                    <g id="svg_7">
                        <circle id="svg_8" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="64.01907" cy="18.92719" r="9.8995"/>
                        <ellipse id="svg_9" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="63.06885" cy="17.56707" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 63.0689 17.5671)" stroke="#000000"/>
                        <ellipse id="svg_10" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="122.01907" cy="38.92719"/>
                        <ellipse id="svg_11" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="61.76907" cy="18.67719"/>
                        <ellipse id="svg_12" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="52.26907" cy="19.67719"/>
                    </g>
                    <g id="svg_13">
                        <circle id="svg_14" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="17.26909" cy="61.67718" r="9.8995"/>
                        <ellipse id="svg_15" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="16.31887" cy="60.31706" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 16.3189 60.3171)" stroke="#000000"/>
                        <ellipse id="svg_16" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="75.26909" cy="81.67718"/>
                        <ellipse id="svg_17" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="15.01909" cy="61.42718"/>
                        <ellipse id="svg_18" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="5.51909" cy="62.42718"/>
                    </g>
                    <g id="svg_19">
                        <circle id="svg_20" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="17.15364" cy="18.76575" r="9.8995"/>
                        <ellipse id="svg_21" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="16.20342" cy="17.40563" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 16.2034 17.4057)" stroke="#000000"/>
                        <ellipse id="svg_22" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="75.15364" cy="38.76575"/>
                        <ellipse id="svg_23" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="14.90364" cy="18.51575"/>
                        <ellipse id="svg_24" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="5.40364" cy="19.51575"/>
                    </g>
                    <g id="svg_25">
                        <circle id="svg_26" fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null" stroke-linecap="null" cx="63.15364" cy="61.76575" r="9.8995"/>
                        <ellipse id="svg_27" fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="62.20342" cy="60.40563" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 62.2034 60.4057)" stroke="#000000"/>
                        <ellipse id="svg_28" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="121.15364" cy="81.76575"/>
                        <ellipse id="svg_29" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="60.90364" cy="61.51575"/>
                        <ellipse id="svg_30" fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null" stroke-linecap="null" cx="51.40364" cy="62.51575"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 6 -->
    <div id="dice6">
        <div class="dice float">
            <svg width="80" height="80" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_37">
                        <circle r="9.8995" cy="40.89076" cx="62.02863" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_38"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 61.078 39.531)" ry="7.94552" rx="9.59012"
                                 cy="39.53064" cx="61.07841" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_39"/>
                        <ellipse cy="60.89076" cx="120.02863" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_40"/>
                        <ellipse cy="40.64076" cx="59.77863" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_41"/>
                        <ellipse cy="41.64076" cx="50.27863" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_42"/>
                    </g>
                    <g id="svg_43">
                        <circle r="9.8995" cy="40.72933" cx="18.16319" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_44"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 17.213 39.369)" ry="7.94552" rx="9.59012"
                                 cy="39.36921" cx="17.21297" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_45"/>
                        <ellipse cy="60.72933" cx="76.16319" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_46"/>
                        <ellipse cy="40.47933" cx="15.91319" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_47"/>
                        <ellipse cy="41.47933" cx="6.41319" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                                 stroke-width="0" stroke="#000000" fill="#353535" id="svg_48"/>
                    </g>
                    <g id="svg_49">
                        <circle r="9.8995" cy="15.87265" cx="62.03354" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_50"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 61.0829 14.5129)" ry="7.94552" rx="9.59012"
                                 cy="14.51253" cx="61.08332" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_51"/>
                        <ellipse cy="35.87265" cx="120.03354" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_52"/>
                        <ellipse cy="15.62265" cx="59.78354" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_53"/>
                        <ellipse cy="16.62265" cx="50.28354" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_54"/>
                    </g>
                    <g id="svg_55">
                        <circle r="9.8995" cy="15.71122" cx="18.16811" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_56"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 17.2179 14.3509)" ry="7.94552" rx="9.59012"
                                 cy="14.3511" cx="17.21789" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                                 stroke-width="0" fill="#353535" id="svg_57"/>
                        <ellipse cy="35.71122" cx="76.16811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_58"/>
                        <ellipse cy="15.46122" cx="15.91811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_59"/>
                        <ellipse cy="16.46122" cx="6.41811" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                                 stroke-width="0" stroke="#000000" fill="#353535" id="svg_60"/>
                    </g>
                    <g id="svg_61">
                        <circle r="9.8995" cy="65.37265" cx="62.03354" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_62"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 61.0829 64.0129)" ry="7.94552" rx="9.59012"
                                 cy="64.01253" cx="61.08332" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_63"/>
                        <ellipse cy="85.37265" cx="120.03354" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_64"/>
                        <ellipse cy="65.12265" cx="59.78354" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_65"/>
                        <ellipse cy="66.12265" cx="50.28354" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_66"/>
                    </g>
                    <g id="svg_67">
                        <circle r="9.8995" cy="65.21122" cx="18.16811" stroke-linecap="null" stroke-linejoin="null"
                                stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#1e1e1e" id="svg_68"/>
                        <ellipse stroke="#000000" transform="rotate(-34.9393 17.2179 63.8509)" ry="7.94552" rx="9.59012"
                                 cy="63.8511" cx="17.21789" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                                 stroke-width="0" fill="#353535" id="svg_69"/>
                        <ellipse cy="85.21122" cx="76.16811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_70"/>
                        <ellipse cy="64.96122" cx="15.91811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_71"/>
                        <ellipse cy="65.96122" cx="6.41811" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null"
                                 stroke-width="0" stroke="#000000" fill="#353535" id="svg_72"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

</div>
@stop