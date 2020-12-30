CKEDITOR.dialog.add( 'dialog', function( editor ) {
	var propValue 	= '',
		name 		= '';
	return {
		title: 	'Микроразметка',
		width: 	1000,
		height: 300,
		contents : [
			{
				id : 'UchredLaw',
				label : 'Основные сведения',
				elements : [
					{
						type : 'radio',
						id : 'UchredLaw',
						label : 'Список',
						items: [['Дата создания образовательной организации', 'RegDate']],
						onClick: function() {
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'UchredLaw',
						items:[['Информация о месте нахождения','Address']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
							console.log(this);
						}
					},
					{
						type: 'radio',
						id: 'UchredLaw',
						items:[['Информация о режиме и графике работы ','WorkTime']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'UchredLaw',
						items:[['Информация о контактных телефонах образовательной организации','Telephone']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id: 'UchredLaw',
						items:[['Информация об адресах электронной почты','E-mail']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'UchredLaw',
						items:[['Информация о месте нахождения филиалов образовательной организации (при наличии)','AddressFil']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'UchredLaw',
						items:[['Информация об учредителе (учредителях) образовательной организации','/UchredLaw']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					}
				]
			},
			{
				id:'struct',
				label:'Структура и органы управления',
				elements:[
					{
						type: 'radio',
						id: 'struct.field',
						items: [['Наименование структурного подразделения','Name']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'struct.field',
						items: [['Информация о руководителях структурных подразделений','Fio']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'struct.field',
						items: [['Информация о местах нахождения структурных подразделений','AddressStr']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'struct.field',
						items: [['Информация об адресах официальных сайтов в сети "Интернет" структурных подразделений','Site']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'struct.field',
						items: [['Информация об адресах электронной почты структурных подразделений','E-mail']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'struct.field',
						items: [['Сведения о наличии положений о структурных подразделениях (об органах управления) с приложением копий указанных положений (при их наличии)','DivisionClause_DocLink']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id: 'doc',
				label: 'Документы',
				style:'overflow:auto;',
				elements:[
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия устава образовательной организации','Ustav_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия лицензии на осуществление образовательной деятельности (с приложениями)','License_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия свидетельства о государственной аккредитации (с приложениями)','Accreditation_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия локального нормативного акта, регламентирующего правила приема обучающихся','Priem_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия локального нормативного акта, регламентирующего режим занятий обучающихся','Mode_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия локального нормативного акта, регламентирующего формы, периодичность и порядок<br>текущего контроля успеваемости и промежуточной аттестации обучающихся','Tek_kontrol_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия локального нормативного акта, регламентирующего порядок и основания перевода,<br>отчисления и восстановления обучающихся','Perevod_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия локального нормативного акта, регламентирующего порядок оформления возникновения,<br>приостановления и прекращения отношений между образовательной организацией и обучающимися и (или) родителями (законными представителями) несовершеннолетних обучающихся','Voz_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия плана финансово-хозяйственной деятельности образовательной организации, утвержденного<br> в установленном законодательством Российской Федерации порядке, или бюджетных смет образовательной организации','FinPlan_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия правил внутреннего распорядка обучающихся','LocalActStud']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия правил внутреннего трудового распорядка','LocalActOrder']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия коллективного договора','LocalActCollec']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Копия локального нормативного акта, регламентирующего размер платы за пользование жилым помещением<br>и коммунальные услуги в общежитии','LocalActObSt']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Отчет о результатах самообследования','ReportEdu_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Документ о порядке оказания платных образовательных услуг, в том числе образец договора об оказании<br> платных образовательных услуг, документ об утверждении стоимости обучения по каждой образовательной программе','PaidEdu_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Предписания органов, осуществляющих государственный контроль (надзор) в сфере образования','Prescription_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'doc.field',
						items: [['Отчеты об исполнении предписаний органов, осуществляющих государственный контроль (надзор) в<br> сфере образования','Prescription_Otchet_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id:'edu',
				label:'Оброзование',
				elements:[
					{
						type:'radio',
						id:'edu.field',
						items:[['Информация о реализуемых уровнях образования','EduLevel']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'edu.field',
						items:[['Информация о формах обучения','EduForm']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'edu.field',
						items:[['Информация о нормативных сроках обучения','LearningTerm']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'edu.field',
						items:[['Информация о сроке действия государственной аккредитации','DateEnd']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id:'eduProg',
				label:'По каждой образовательной программе',
				elements:[
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Уровень образования','EduLavel']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Код специальности, направления подготовки','EduCode']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация об описании образовательной программы','OOP_main']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация об учебном плане','education_plan']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация об аннотации к рабочим программам','education_annotation']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о календарном учебном графике','education_shedule']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о методических и об иных документах','methodology']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о практиках','EduPr']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о численности обучающихся','BudgAmount']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['численности лиц, находящихся на платном обучении','PaidAmount']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о языках, на которых осуществляется образование','language']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о направлениях и результатах научной деятельности','/NIR']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о результатах приема по каждому направлению ','/priem']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduPro.field',
						items:[['Информация о результатах перевода, восстановления и отчисления','/Perevod']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id:'eduStand',
				label:'Образовательные стандарты',
				elements:[
					{
						type:'radio',
						id:'eduStand.field',
						items:[['Копии федеральных государственных образовательных стандартов ','EduStandartDoc']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id:'eduTools',
				label:'Материально-техническое обеспечение ',
				elements:[
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения о наличии оборудованных учебных кабинетов','PurposeKab']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения о наличии объектов для проведения практических занятий','PurposePrac']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения о наличии библиотек','PurposeLibr']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения о наличии объектов спорта','PurposeSport']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения о наличии средств обучения и воспитания','PurposeSport']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения об условиях питания и охраны здоровья обучающихся','Meals']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения о доступе к информационным системам и информационно-телекоммуникационным сетям','ComNet']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'eduTools.field',
						items:[['Сведения об электронных образовательных ресурсах','ERList']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id:'stepend',
				label:'Стипендии и иные виды материальной поддержки',
				elements:[
					{
						type:'radio',
						id:'stepend.field',
						items:[['Информация о наличии и условиях предоставления стипендий','/Grant']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'stepend.field',
						items:[['Информация о наличии общежития, интерната','HostelInfo']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'stepend.field',
						items:[['Информация о количестве жилых помещений в общежитии','HostelNum']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'stepend.field',
						items:[['Копия локального нормативного акта, регламентирующего размер платы за пользование жилым помещением и коммунальные услуги в общежитии','LocalActObSt']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'stepend.field',
						items:[['Информация об иных видах материальной поддержки обучающихся','Support']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'stepend.field',
						items:[['Информация о трудоустройстве выпускников','GraduateJob']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id:'pay',
				label:'Платные образовательные услуги',
				elements:[
					{
						type:'radio',
						id:'pay.field',
						items:[['Документ о порядке оказания платных образовательных услуг','PaidEdu_DocLink']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					}
				]
			},
			{
				id:'financ',
				label:'Финансово-хозяйственная деятельность',
				elements:[
					{
						type:'radio',
						id:'financ.field',
						items:[['Объем оброзовательной деятельности за счет бюджетных вакансий','/Volume']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'financ.field',
						items:[['Информация о поступлении и расходовании финансовых и материальных средств','/FinRec']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
				]
			},
			{
				id:'vac',
				label:'Вакантные места для приема (перевода)',
				elements:[
					{
						type:'radio',
						id:'vac.field',
						items:[['Информация о количестве вакантных мест для приема','/Vacant']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					}
				]
			},
			{
				id: 'teachers',
				label:'Руководство педагогический состав',
				elements:[
					{
						type: 'radio',
						id: 'teach.sost',
						label: 'Список',
						items:[['Ф.И.О. педагогического работника ','fio']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'teach.sost',
						items:[['Занимаемая должность','Post']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type: 'radio',
						id: 'teach.sost',
						items:[['Преподаваемые педагогическим работником дисциплины','TeachingDiscipline']],
						onClick:function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Ученая степень педагогического работника (при наличии)','Degree']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Ученое звание педагогического работника','AcademStat']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Наименование направления подготовки и (или) специальности педагогического работника','EmployeeQualification']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Данные о повышении квалификации и (или) профессиональной переподготовке педагогического работника','ProfDevelopment']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Общий стаж работы','GenExperience']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Стаж работы по специальности','SpecExperience']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Контактный телефон','Telephone']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					},
					{
						type:'radio',
						id:'teach.sost',
						items:[['Адрес E-mail','e-mail']],
						onClick: function(){
							name = this.items[0][0];
							propValue = this.getValue();
						}
					}
				]
			}
		],
		onShow : function(){
			var style 		= document.createElement('style');
			style.id 		='temp-ck-styles';
			style.innerHTML ='display:inline-block;}.cke_reset_all *{height:100%;}.cke_dialog_page_contents{overflow:auto!important;width:1000px!important;height:50vh!important;min-height:300px!important;}.cke_dialog_tabs{width: 1000px!important;word-wrap: normal!important;white-space: normal!important;height: auto !important;position: static!important;margin: 5px auto!important;display: block!important;}';
			document.body.appendChild(style);
			activeTab = this._.currentTabId;
			var i = 0;
			for(; i<this.definition.contents.length;i++){
					if(this.definition.contents[i].id == activeTab)
						break;
			}
			haveMicrotags(this.definition.contents[i],editor.document.find('.microtag').$,this);
		},
		onOk : function(){
			var selectedText = editor.getSelection().getSelectedText(),
				microType = CKEDITOR.dialog.getCurrent().definition.dialog._.currentTabId,
				div = '',
				propName = '',
				newElement = new CKEDITOR.dom.element("span");

			if(propValue.indexOf("/") > -1){
				propName = 'itemtype';
				propValue = 'http://obrnadzor.gov.ru/microformats'+propValue;
			}
			else
				propName = 'itemprop';

			newElement.setText(selectedText);
			newElement.setAttribute(propName,propValue);
			newElement.setAttribute("class","microtag");
			newElement.setAttribute("title",name);
			editor.insertElement(newElement);

			var el = document.getElementById('temp-ck-styles');
			el.parentNode.removeChild(el);
		},
		onCancel : function(){
			var el = document.getElementById('temp-ck-styles');
			el.parentNode.removeChild(el);
		},
		onLoad : function(){
			dialog = this;
			dialog.on('selectPage',function(e){

				if(CKEDITOR.env.ie7Compact)
					fixIE7display;

					activeTab = e.data.page;
					var i = 0;
					for(; i < this.definition.contents.length;i++){
						if(this.definition.contents[i].id == activeTab)
							break;
					}
					haveMicrotags(this.definition.contents[i],editor.document.find('.microtag').$,dialog);
			});
		}
    };
});

function haveMicrotags(activeTab,ed,dialog){
	
	var arr = {};
	for(var i = 0; i < activeTab.elements.length;i++){
		arr[activeTab.elements[i].items[0][0]] = 0;
	}

	for(var j = 0;j < ed.length;j++){
		if(ed[j].hasAttribute('title') && ed[j].getAttribute('title') in arr )
			arr[ed[j].getAttribute('title')] = 1;
	}
	var labels = document.querySelector('.cke_dialog_contents').querySelectorAll('label[id][class][for]');
	for(var j = 0; j < labels.length;j++){
		for(el in arr){
				if(arr[el] == 1 && labels[j].innerHTML == el){
					labels[j].innerHTML = labels[j].innerHTML+' &#10004;';
					labels[j].className = 'galka';
				}
		}
	}
}
