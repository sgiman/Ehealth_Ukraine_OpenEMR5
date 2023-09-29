<!-- //////////////////////////////////////////// SIGN ////////////////////////////////////////////-->
<div class="modal fade" id="notLoadedSignLibModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <h4 class="modal-title">Увага</h4>
    </div>
    <div class="modal-body">
        <p>
            <i class="glyph-icon icon-warning font-size-35 font-blue"></i>
            <span id="notLoadedSignLibText"></span>
        </p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Так</button>
    </div>
</div>

<div class="modal modal-lg fade" id="SigningModal" tabindex="-1"
     data-focus-on="select:first" style="display: none;">
    <div class="modal-header">
        <h4 class="modal-title">Налаштування КЕП (кваліфікований електронний підпис)</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal mrg10A" id="myModalForm">
            <div class="form-group">
                <div class="row">
                    <label class="col-md-3 control-label">АЦСК:</label>
                    <div class="col-md-9">
                        <select class="form-control single" id="CAsServersSelect"></select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-3 control-label">Приватний ключ:</label>
                    <div class="col-md-9">
                        <input type="radio" id="keyInFile" name="typeOfKey" value="keyInFile" checked>
                        <label for="keyInFile"> у файлі </label>
                        <input type="radio" id="protectedKey" name="typeOfKey" value="protectedKey">
                        <label for="protectedKey"> на захищеному носії</label>
                        <div class="loading-spinner hide" id="loadingProtKey"> (завантажуються данні..)</div>
                    </div>
                </div>

                <div class="row" id="divForKeyInFile">
                    <div class="input-group col-md-9 col-md-offset-3 input-file" name="Fichier1">
                        <input type="text" class="form-control" placeholder='Оберіть файл' />
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-choose" onclick="bs_input_file();"
                                    type="button">
                                Обрати
                            </button>
                        </span>
                    </div>
                </div>					

                <div class="form-group" id="divForProtectedKey" hidden>
                    <div class="row">
                        <label class="col-md-3 control-label">Тип носія: </label>
                        <div class="col-md-9">
                            <select class="form-control single" id="kmTypes"></select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 control-label">Носій: </label>
                        <div class="col-md-9">
                            <select class="form-control single" id="kmDevices"></select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label class="col-md-3 control-label">Пароль:</label>
                    <div class="col-md-9">
                        <input type="password" class="form-control" id="kmPassword">
                    </div>
                </div>
            </div>

            <div id="chooseJKSAlias" class="modal fade" tabindex="-1"
                 data-focus-on="select:first" style="display: none;">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Оберіть назву потрібного ключа з контейнеру, що знаходиться в обраному файлі
                    </h4>
                </div>

                <div class="modal-body">
                    <select class="form-control single" id="JKSAliasSelect">
                        <!--<option>1</option><option>2</option>-->
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" id="JKSAliasSelectButton"
                            class="btn btn-primary">
                        Вибрати
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div class="modal-footer">
        <div class="loading-spinner align-middle hide" id="loaderStatus"></div>
        <button type="button" class="btn btn-primary" id="signFile"
                data-bind="click: onclickSignFile">
            Підписати
        </button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Відмінити</button>
    </div>

</div>

<!-- ====================== Agreement Modal (MODAL) ====================== -->
<script src="js/viewmodels/agreementModal.js"></script>

<div class="modal fade" id="AgreementModal" tabindex="-1" data-focus-on="input:first" style="display: none;" data-bind="with: agreementModal">
    <div class="modal-header">
        <h4 class="modal-title" data-bind="text: headerText"></h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal col-md-12">
            <div class="form-group">
                <div class="scrollable-content scrollable-xs scrollable-slim">
                    <span data-bind="text: textOfAgreement"></span>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="checkbox checkbox-primary">
                    <label>
                        <input type="checkbox" data-bind="checked: acceptAgreement" class="custom-checkbox">
                        <span data-bind="text: textNearCheckbox"></span>
                    </label>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bind="click: functionName, enable: acceptAgreement">Погодитись</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Відмовитись</button>
    </div>
</div>
<!-- ////////////////////////////////////////// END SIGN /////////////////////////////////////////-->
