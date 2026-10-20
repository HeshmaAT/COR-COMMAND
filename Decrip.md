استخدمنا command علشان نفصل كل action عن التانيه وال invoker متكونش عارفه اي حاجه عن طريقه التنفيذ

وكمان علشان نقدر نعمل undo ونحتفظ بال history بتاعت العمليات

واستخدمنا cor علشان بدل ما نحط كل ال validations في مكان واحد نقسمها علي handlers

كل handler مسؤله عن شرط معين ولو فشل هتوقف السلسله كلها

وبكدا نقدر نزود او نشيل او نغير ترتيب اي handler من غير ما نعدل في الباقي

ومن غير ال patterns دي كنا هنلاقي نفسنا بنكتب شروط و logic كتير في مكان واحد وكل ما نحب نزود حاجه هنحتاج نعدل في كذا مكان

/*
Some OF Notes

كان تركيزنا الاساسي في التاسك دي علي تطبيق Command Pattern و Chain of Responsibility
وفهم ازاي نربط الاتنين ببعض مع الحفاظ علي فصل المسؤليات

مش هدفنا نبني Order Processing System كامل 

علشان كدا فيه شوية حاجات فكستلها في ال flow الحقيقي

علي سبيل مثال شويه edge cases في بالي واانا بعمل FLOW 

1- زي لو اتنين users طلبوا اخر قطعه من نفس المنتج في نفس الوقت ودي محتاجه Concurrency Control و Database Locking

2- لو نفس request اتبعت اكتر من مره هنحتاج Idempotency Key علشان نمنع تكرار انشاء الاوردر او الدفع

3- استخدام Database Transactions علشان نضمن ان انشاء الاوردر وخصم ال stock يحصلوا مع بعض ولو حصل error نعمل rollback

4- جزء حجز ال stock لفتره معينه لحد ما العميل يكمل الدفع ولو مدفعش نرجع الكميه تاني

5- التاكد من ان ال user هو فعلا صاحب الاوردر قبل ما يعمل cancel او pay او apply discount

6- التعامل مع الحالات المختلفه لل order وال payment بشكل كامل زي pending و processing و failed و cancelled و expired

7- التعامل مع ال undo في حالات زي refund او تغيير حالة الشحن لان مش كل عمليه ينفع نرجعها بمجرد تغيير status


كل النقط دي مهمه جدا في production لكن انا فكستلها علشان كان تركيز الاساسي ازاي اطبق DPs بشكل سيلم واركبهم علي بعض زي Puzzle

وهو اننا نفهم ونطبق COR و Command بشكل عملي ونخلي كل pattern مسؤله عن الجزء الخاص بيها

*/





/* implement

1- اول حاجه عندنا user يقدر يعمل place order او cancel order او pay order او apply discount

2- فبدأنا عملنا classes لل product و order و discount وخزنا فيهم الداتا من غير database

3- بعد كدا عملنا IOrderCommand فيها execute علشان اي command نعملها بعد كدا تكون ماشيه علي نفس النظام

4- وعملنا 4 commands كل واحده مسؤله عن action معينه

5- بعد كدا عملنا CommandInvoker وهي الي بتنفذ اي command من غير ما تعرف تفاصيل تنفيذها وكمان بتحتفظ بال history

6- وزودنا IUndoableCommand علشان لو عندنا command ينفع نرجع فيها زي cancel order و apply discount

7- نيجي بقي لل cor عملنا AbstractHandler مسؤله عن ربط ال handlers ببعض وكل handler مسؤله عن validation واحده بس

8- يعني مثلا في place order بنتاكد الاول من الايميل بعد كدا المنتج موجود ولا لا وبعدها نشوف الكميه متاحه ولا لا

9- لو اي handler فشلت بنوقف السلسله ونرمي exception فيها سبب المشكله واسم ال handler الي وقفت عندها

10- بعد كدا عملنا services لكل action وكل service مسؤله عن ترتيب ال handlers بتاعتها حسب الي محتاجاه

11- ولما كل ال validations تعدي بنجاح ال service بتبعت ال command لل invoker علشان تتنفذ

12- في place order بنعمل order جديده حالتها pending وبنخصم الكميه من stock

13- في cancel order بنتاكد ان الاوردر مش confirmed او paid وبعد كدا بنخلي حالتها cancelled ونرجع الكميه لل stock

14- ولو عمل undo لل cancel بنتاكد من الكميه تاني من غير email validation وبعدها بنرجع نفس الاوردر pending ونحجز الكميه تاني

15- في apply discount بنتاكد ان الكوبون valid وان الاوردر لسه pending ومفيش عليها خصم قبل كدا وبعدها بنحسب السعر الجديد

16- ولو عمل undo لل discount بنشيل الكوبون ونرجع السعر القديم ويقدر بعدها يحط كوبون تاني

17- في pay order بنتاكد ان الاوردر ينفع تتدفع وبنتاكد من رقم الفيزا وبعدها بنخلي الاوردر confirmed و paid

18- وبالنسبه لل undo خليناها تشتغل علي اخر command اتنفذت بس ولو مش بتدعم undo بنرجع error باسمها

19- وفي الاخر عملنا client بسيطه ب while true علشان نجرب كل العمليات ونقدر نعرض المنتجات والاوردرات بتفاصيلها

20- وزودنا logger بسيطه بتسجل اي command اتنفذت او اتعملها undo وكمان اي request اترفضت من handler وسبب الرفض

*/






