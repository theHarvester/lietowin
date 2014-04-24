@extends('master.page')
@section('head')
{{HTML::script('js/game.js')}}
@stop
@section('content')
<script type="text/javascript">
    var username = "{{ $username }}";
</script>
<div id="body-container">
    <div><h1>Lie to Win</h1></div>
    <div id="currentlyQueued">You are in the queue, please wait while we find you a game.</div>
    <div id="turnFormContainer">
        <div id="turnForm">
            <div class="turnForms">
                <form id="turnFormRaise" action="/apifight/public/api/v1/game/move" type="post">
                    <div class="clear"></div>
                    <input id="dice_number" type="hidden" name="dice_number">
                    <input id="amount" type="hidden" name="amount">
                    <input id="raise" type="hidden" name="call" value="raise" checked="checked">
                    <a href="#" class="button">Raise</a>
                </form>

                <form id="turnFormLie" action="/apifight/public/api/v1/game/move" type="post">
                    <input type="hidden" name="call" value="lie">
                    <a href="#" class="button">Lie</a>
                </form>

                <form id="turnFormPerfect" action="/apifight/public/api/v1/game/move" type="post">
                    <input type="hidden" name="call" value="perfect">
                    <a href="#" class="button">Spot on</a>
                </form>
            </div>
            <div class="diceRaiseRow">
                <div id="diceAmt" class="diceColumn">
                    <div class="arrowBase arrowUp raiseArrow">&nbsp;</div>
                    <div id="raiseDiceAmount" class="diceReserve"></div>
                    <div class="arrowBase arrowDown lowerArrow">&nbsp;</div>
                </div>
                <div id="diceNum" class="diceColumn">
                    <div class="arrowBase arrowUp raiseArrow">&nbsp;</div>
                    <div id="raiseDiceNumber" class="diceReserve"></div>
                    <div class="arrowBase arrowDown lowerArrow">&nbsp;</div>
                </div>
            </div>

        </div>
    </div>
    <div class="clear"></div>

    <div id="myDice">
        <div id="myDiceName" class="username">You</div>
        <div id="myDiceRow"></div>
    </div>
    <div class="clear"></div>

    <div id="diceAvailable"></div>
    <div class="clear"></div>

    <div id="moveHistory"></div>
    <div id="roundResultContainer">
        <div id="roundResult" class="white_content">
            <div class="exit">X</div>
            <div class="content"></div>

            <a href="#" onclick="closeLightBox()" class="button close-light-box">Close</a>
        </div>
        <div class="black_overlay"></div>
    </div>
    <div id="output"></div>
</div>

<div id="diceSvg">
    <!-- Empty Dice -->
    <div id="emptyDice">
        <div class="dice float textContent">
            &nbsp;
        </div>
    </div>

    <!-- Skull -->
    <div id="diceSkull">
        <div class="dice float">
            <svg width="80.00000000000001" height="80.00000000000001" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g display="inline">
                    <title>crossbones</title>
                    <rect stroke="#333333" transform="rotate(49.4917 40.1063 40.1533)" id="svg_27" height="69.89625" width="7.875" y="5.20515" x="36.16883" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="#ffffff"/>
                    <rect id="svg_30" stroke="#333333" transform="rotate(130.875 40.9375 39.5937)" height="69.89625" width="7.875" y="4.64561" x="37.00001" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="#ffffff"/>
                    <ellipse id="svg_34" ry="5.625" rx="5.0625" cy="13.84375" cx="17.08334" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff"/>
                    <ellipse ry="5.625" rx="5.0625" cy="19.83333" cx="12" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff" id="svg_1"/>
                    <rect fill="#ffffff" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null" stroke-linecap="null" x="14.05791" y="13.09917" width="5.49343" height="11.00429" id="svg_3" transform="rotate(-49.0039 16.8046 18.6013)" stroke="#333333"/>
                    <ellipse ry="5.625" rx="5.0625" cy="66.16667" cx="65" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff" id="svg_5"/>
                    <ellipse ry="5.625" rx="5.0625" cy="60.5" cx="71.33333" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff" id="svg_6"/>
                    <rect fill="#ffffff" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null" stroke-linecap="null" x="62.79092" y="55.26762" width="5.66414" height="11.50297" transform="rotate(-50.1946 65.623 61.0191)" id="svg_8" stroke="#333333"/>
                    <ellipse ry="5.625" rx="5.0625" cy="64.83333" cx="16.66667" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff" id="svg_9"/>
                    <ellipse ry="5.625" rx="5.0625" cy="59.83333" cx="10.66667" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff" id="svg_10"/>
                    <ellipse ry="5.625" rx="5.0625" cy="14.5" cx="65" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff" id="svg_11"/>
                    <ellipse ry="5.625" rx="5.0625" cy="19.83333" cx="70.66667" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#333333" fill="#ffffff" id="svg_12"/>
                    <rect fill="#ffffff" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null" stroke-linecap="null" x="61.95791" y="13.31681" width="5.92021" height="11.50297" transform="rotate(50.1945 64.918 19.0683)" id="svg_13" stroke="#333333"/>
                    <rect fill="#ffffff" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null" stroke-linecap="null" x="13.70657" y="54.41516" width="5.92021" height="11.50297" transform="rotate(50.1945 16.6667 60.1667)" stroke="#333333" id="svg_16"/>
                </g>
                <g display="inline">
                    <title>skull</title>
                    <ellipse stroke="#333333" ry="19.83139" rx="23.6875" id="svg_2" cy="40.35974" cx="39.99998" stroke-width="2" fill="#ffffff"/>
                    <rect id="svg_7" height="11" width="21" y="54.59375" x="29.41665" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="#ffffff" stroke="#333333"/>
                    <rect id="svg_14" height="4.25" width="23.79167" y="52.21875" x="28.41666" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#ffffff" stroke="#ffffff"/>
                    <ellipse stroke="#333333" ry="7.3125" rx="7.3125" id="svg_15" cy="41.34375" cx="30.99998" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="#ffffff"/>
                    <ellipse id="svg_17" cy="27.34375" cx="27.62498" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#939292" fill="#cccccc"/>
                    <ellipse stroke="#333333" id="svg_20" ry="5.625" rx="5.625" cy="41.15625" cx="51.62501" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="#ffffff"/>
                </g>
            </svg>
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
                    <ellipse stroke="#000000" transform="rotate(-34.9393 39.5498 39.1399)" ry="7.94552" rx="9.59012"
                             id="svg_3"
                             cy="39.13988" cx="39.54978" stroke-linecap="null" stroke-linejoin="null"
                             stroke-dasharray="null"
                             stroke-width="0" fill="#353535"/>
                    <ellipse id="svg_4" cy="60.5" cx="98.5" stroke-linecap="null" stroke-linejoin="null"
                             stroke-dasharray="null"
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
        <div class="dice float">
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
                        <ellipse cy="62.42718" cx="5.51909" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null"
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
                        <circle fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null"
                                stroke-linecap="null" cx="40.5" cy="40.5" r="9.8995" id="svg_1"/>
                        <ellipse fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="39.54978" cy="39.13988" id="svg_3" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 39.5498 39.1399)" stroke="#000000"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null"
                                 stroke-linecap="null" cx="98.5" cy="60.5" id="svg_4"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null"
                                 stroke-linecap="null" cx="38.25" cy="40.25" id="svg_5"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null"
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
                        <circle fill="#1e1e1e" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                stroke-linejoin="null"
                                stroke-linecap="null" cx="40.5" cy="40.5" r="9.8995" id="svg_1"/>
                        <ellipse fill="#353535" stroke-width="0" stroke-dasharray="null" stroke-linejoin="null"
                                 stroke-linecap="null" cx="39.54978" cy="39.13988" id="svg_3" rx="9.59012" ry="7.94552"
                                 transform="rotate(-34.9393 39.5498 39.1399)" stroke="#000000"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null"
                                 stroke-linecap="null" cx="98.5" cy="60.5" id="svg_4"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null"
                                 stroke-linecap="null" cx="38.25" cy="40.25" id="svg_5"/>
                        <ellipse fill="#353535" stroke="#000000" stroke-width="0" stroke-dasharray="null"
                                 stroke-linejoin="null"
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
                        <ellipse cy="41.47933" cx="6.41319" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null"
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
                                 cy="14.3511" cx="17.21789" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null"
                                 stroke-width="0" fill="#353535" id="svg_57"/>
                        <ellipse cy="35.71122" cx="76.16811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_58"/>
                        <ellipse cy="15.46122" cx="15.91811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_59"/>
                        <ellipse cy="16.46122" cx="6.41811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null"
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
                                 cy="63.8511" cx="17.21789" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null"
                                 stroke-width="0" fill="#353535" id="svg_69"/>
                        <ellipse cy="85.21122" cx="76.16811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_70"/>
                        <ellipse cy="64.96122" cx="15.91811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_71"/>
                        <ellipse cy="65.96122" cx="6.41811" stroke-linecap="null" stroke-linejoin="null"
                                 stroke-dasharray="null"
                                 stroke-width="0" stroke="#000000" fill="#353535" id="svg_72"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- Empty -->
    <div id="emptyDiceSmall">
        <div class="diceSmall float textContent">

        </div>
    </div>

    <!-- 1 -->
    <div id="diceSmall1">
        <div class="diceSmall float">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_37">
                        <ellipse cy="55.69076" cx="116.22863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_40"/>
                        <ellipse cy="35.44076" cx="55.97863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_41"/>
                        <ellipse cy="36.44076" cx="46.47863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_42"/>
                    </g>
                    <g id="svg_19">
                        <circle r="4.96893" cy="19.73498" cx="19.96183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_20"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="14.21422" cx="10.61194" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_21"/>
                        <ellipse cy="28.03061" cx="40.40221" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_22"/>
                        <ellipse cy="21.63979" cx="21.38758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_23"/>
                        <ellipse cy="21.95539" cx="18.38942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_24"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 2 -->
    <div id="diceSmall2">
        <div class="diceSmall float">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_37">
                        <ellipse cy="55.69076" cx="116.22863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_40"/>
                        <ellipse cy="35.44076" cx="55.97863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_41"/>
                        <ellipse cy="36.44076" cx="46.47863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_42"/>
                    </g>
                    <g id="svg_19">
                        <circle r="4.96893" cy="34.73498" cx="6.96183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_20"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="29.58574" cx="-50.376" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_21"/>
                        <ellipse cy="43.03061" cx="27.40221" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_22"/>
                        <ellipse cy="36.63979" cx="8.38758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_23"/>
                        <ellipse cy="36.95539" cx="5.38942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_24"/>
                    </g>
                    <g id="svg_1">
                        <circle r="4.96893" cy="5.41336" cx="33.76183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_2"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="2.05655" cx="72.44681" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_3"/>
                        <ellipse cy="13.70899" cx="54.2022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_4"/>
                        <ellipse cy="7.31818" cx="35.18758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_5"/>
                        <ellipse cy="7.63378" cx="32.18942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_6"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 3 -->
    <div id="diceSmall3">
        <div class="diceSmall float">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_37">
                        <ellipse cy="55.69076" cx="116.22863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_40"/>
                        <ellipse cy="35.44076" cx="55.97863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_41"/>
                        <ellipse cy="36.44076" cx="46.47863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_42"/>
                    </g>
                    <g id="svg_19">
                        <circle r="4.96893" cy="34.73498" cx="6.96183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_20"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="29.58574" cx="-50.376" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_21"/>
                        <ellipse cy="43.03061" cx="27.40221" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_22"/>
                        <ellipse cy="36.63979" cx="8.38758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_23"/>
                        <ellipse cy="36.95539" cx="5.38942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_24"/>
                    </g>
                    <g id="svg_1">
                        <circle r="4.96893" cy="5.41336" cx="33.76183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_2"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="2.05655" cx="72.44681" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_3"/>
                        <ellipse cy="13.70899" cx="54.2022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_4"/>
                        <ellipse cy="7.31818" cx="35.18758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_5"/>
                        <ellipse cy="7.63378" cx="32.18942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_6"/>
                    </g>
                    <g id="svg_7">
                        <circle id="svg_8" r="4.96893" cy="20.63498" cx="20.40433" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000"/>
                        <ellipse id="svg_9" transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="17.35498" cx="10.1281" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                        <ellipse id="svg_10" cy="28.9306" cx="40.8447" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                        <ellipse id="svg_11" cy="22.53979" cx="21.83007" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                        <ellipse id="svg_12" cy="22.85539" cx="18.83191" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                    </g>
                </g>
            </svg>

        </div>
    </div>

    <!-- 4 -->
    <div id="diceSmall4">
        <div class="diceSmall float">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_37">
                        <ellipse cy="55.69076" cx="116.22863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_40"/>
                        <ellipse cy="35.44076" cx="55.97863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_41"/>
                        <ellipse cy="36.44076" cx="46.47863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_42"/>
                    </g>
                    <g id="svg_55">
                        <circle r="4.96893" cy="5.41339" cx="6.96188" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" id="svg_56" stroke="#000000"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="-46.57708" cx="2.83382" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_57" stroke="#000000"/>
                        <ellipse cy="13.70901" cx="27.40225" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_58" stroke="#000000"/>
                        <ellipse cy="7.3182" cx="8.38762" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_59" stroke="#000000"/>
                        <ellipse cy="7.6338" cx="5.38946" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_60" stroke="#000000"/>
                    </g>
                    <g id="svg_19">
                        <circle r="4.96893" cy="34.73498" cx="6.96183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_20"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="29.58574" cx="-50.376" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_21"/>
                        <ellipse cy="43.03061" cx="27.40221" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_22"/>
                        <ellipse cy="36.63979" cx="8.38758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_23"/>
                        <ellipse cy="36.95539" cx="5.38942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_24"/>
                    </g>
                    <g id="svg_1">
                        <circle r="4.96893" cy="5.41336" cx="33.76183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_2"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="2.05655" cx="72.44681" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_3"/>
                        <ellipse cy="13.70899" cx="54.2022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_4"/>
                        <ellipse cy="7.31818" cx="35.18758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_5"/>
                        <ellipse cy="7.63378" cx="32.18942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_6"/>
                    </g>
                    <g id="svg_25">
                        <circle r="4.96893" cy="34.73498" cx="33.76183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_26"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="78.21954" cx="19.23705" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_27"/>
                        <ellipse cy="43.03061" cx="54.2022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_28"/>
                        <ellipse cy="36.6398" cx="35.18758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_29"/>
                        <ellipse cy="36.9554" cx="32.18942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_30"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 5 -->
    <div id="diceSmall5">
        <div class="diceSmall float">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_37">
                        <ellipse cy="55.69076" cx="116.22863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_40"/>
                        <ellipse cy="35.44076" cx="55.97863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_41"/>
                        <ellipse cy="36.44076" cx="46.47863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_42"/>
                    </g>
                    <g id="svg_55">
                        <circle r="4.96893" cy="5.41339" cx="6.96188" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" id="svg_56" stroke="#000000"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="-46.57708" cx="2.83382" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_57" stroke="#000000"/>
                        <ellipse cy="13.70901" cx="27.40225" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_58" stroke="#000000"/>
                        <ellipse cy="7.3182" cx="8.38762" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_59" stroke="#000000"/>
                        <ellipse cy="7.6338" cx="5.38946" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_60" stroke="#000000"/>
                    </g>
                    <g id="svg_19">
                        <circle r="4.96893" cy="34.73498" cx="6.96183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_20"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="29.58574" cx="-50.376" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_21"/>
                        <ellipse cy="43.03061" cx="27.40221" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_22"/>
                        <ellipse cy="36.63979" cx="8.38758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_23"/>
                        <ellipse cy="36.95539" cx="5.38942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_24"/>
                    </g>
                    <g id="svg_1">
                        <circle r="4.96893" cy="5.41336" cx="33.76183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_2"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="2.05655" cx="72.44681" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_3"/>
                        <ellipse cy="13.70899" cx="54.2022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_4"/>
                        <ellipse cy="7.31818" cx="35.18758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_5"/>
                        <ellipse cy="7.63378" cx="32.18942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_6"/>
                    </g>
                    <g id="svg_25">
                        <circle r="4.96893" cy="34.73498" cx="33.76183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_26"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="78.21954" cx="19.23705" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_27"/>
                        <ellipse cy="43.03061" cx="54.2022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_28"/>
                        <ellipse cy="36.6398" cx="35.18758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_29"/>
                        <ellipse cy="36.9554" cx="32.18942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_30"/>
                    </g>
                    <g id="svg_7">
                        <circle id="svg_8" r="4.96893" cy="20.63498" cx="20.40433" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000"/>
                        <ellipse id="svg_9" transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="17.35498" cx="10.1281" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                        <ellipse id="svg_10" cy="28.9306" cx="40.8447" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                        <ellipse id="svg_11" cy="22.53979" cx="21.83007" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                        <ellipse id="svg_12" cy="22.85539" cx="18.83191" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- 6 -->
    <div id="diceSmall6">
        <div class="diceSmall float">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>Layer 1</title>
                    <g id="svg_37">
                        <ellipse cy="55.69076" cx="116.22863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_40"/>
                        <ellipse cy="35.44076" cx="55.97863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_41"/>
                        <ellipse cy="36.44076" cx="46.47863" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#353535" id="svg_42"/>
                    </g>
                    <g id="svg_55">
                        <circle r="4.96893" cy="5.41339" cx="8.96188" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" id="svg_56" stroke="#000000"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="-42.94769" cx="8.02883" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_57" stroke="#000000"/>
                        <ellipse cy="13.70901" cx="29.40225" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_58" stroke="#000000"/>
                        <ellipse cy="7.3182" cx="10.38762" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_59" stroke="#000000"/>
                        <ellipse cy="7.6338" cx="7.38946" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" id="svg_60" stroke="#000000"/>
                    </g>
                    <g id="svg_7">
                        <circle r="4.96893" cy="20.13498" cx="8.96183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_8"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="-4.7084" cx="-18.68647" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_9"/>
                        <ellipse cy="28.43061" cx="29.40221" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_10"/>
                        <ellipse cy="22.03979" cx="10.38758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_11"/>
                        <ellipse cy="22.35539" cx="7.38942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_12"/>
                    </g>
                    <g id="svg_13">
                        <circle r="4.96893" cy="20.13498" cx="32.36183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_14"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="37.75542" cx="42.09508" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_15"/>
                        <ellipse cy="28.43061" cx="52.8022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_16"/>
                        <ellipse cy="22.03979" cx="33.78758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_17"/>
                        <ellipse cy="22.35539" cx="30.78942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_18"/>
                    </g>
                    <g id="svg_19">
                        <circle r="4.96893" cy="34.73498" cx="8.96183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_20"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="33.21513" cx="-45.18099" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_21"/>
                        <ellipse cy="43.03061" cx="29.40221" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_22"/>
                        <ellipse cy="36.63979" cx="10.38758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_23"/>
                        <ellipse cy="36.95539" cx="7.38942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_24"/>
                    </g>
                    <g id="svg_1">
                        <circle r="4.96893" cy="5.41336" cx="32.36183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_2"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="-0.48402" cx="68.81031" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_3"/>
                        <ellipse cy="13.70899" cx="52.8022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_4"/>
                        <ellipse cy="7.31818" cx="33.78758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_5"/>
                        <ellipse cy="7.63378" cx="30.78942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_6"/>
                    </g>
                    <g id="svg_25">
                        <circle r="4.96893" cy="34.73498" cx="32.36183" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#1e1e1e" stroke="#000000" id="svg_26"/>
                        <ellipse transform="matrix(0.258712 -0.180744 0.180744 0.258712 16.4833 19.5301)" ry="7.94552" rx="9.59012" cy="75.67897" cx="15.60055" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_27"/>
                        <ellipse cy="43.03061" cx="52.8022" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_28"/>
                        <ellipse cy="36.6398" cx="33.78758" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_29"/>
                        <ellipse cy="36.9554" cx="30.78942" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" fill="#353535" stroke="#000000" id="svg_30"/>
                    </g>
                </g>
            </svg>
        </div>
    </div>

</div>
@stop