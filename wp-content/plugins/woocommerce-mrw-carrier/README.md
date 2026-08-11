// ********************************************************************************* //
//  MODULO de WooCommerce para MRW
// ********************************************************************************* //

1.  El módulo hace el uso de la libreria SOAP de PHP para conexion y generacion
    de envios entre WooCommerce y el WebService SAGEC de MRW

// ********************************************************************************* //
//  CHANGELOG - REGISTRO DE CAMBIOS
// ********************************************************************************* //

05/06/2025: 5.13.0 Portugal States fix for new Woocommerce version.

06/05/2025: 5.12.2 Show tracking in classic configuration (no HPOS).

20/04/2025: 5.12.1 Fix pop up label generated.

07/04/2025: 5.12.0 Shipping phone in notifications if exists.

26/12/2025: 5.11.1 Add fields in reinstallation.

09/12/2025: 5.11.0 Frequency option.

20/11/2025: 5.10.1 Fix tracking number update.

15/10/2025: 5.10.0 New option select date, trackin number and tracking email.

29/09/2025: 5.9.0 New option Ecommerce slot by default

20/08/2025: 5.8.3 Accept decimal numbers in freeshipping field

10/03/2025: 5.8.2 Use price with taxes for free shipment

24/02/2025: 5.8.1 Fix MRW by default

28/01/2025: 5.8.0 Fix config save

16/12/2024: 5.7.0 Add terms and conditions

12/11/2024: 5.6.3 Fix duplicate zones and save ranges

17/10/2024: 5.6.2 Delete EURO2 service

21/06/2024: 5.6.0 Add bulk actions when HPOS activated

14/06/2024: 5.5.3 Parse decimals in parcel weight

30/05/2024: 5.5.2 Fix bug order creation.

08/05/2024: 5.5.1 Maritimo canarias con mercancia y valor, quitar todas las referencias del log.

13/02/2024: 5.5.0 Delete Log register (avoid CWE-200)

05/02/2024: 5.4.3 Fix cash on delivery check for Woocommerce > 3

11/01/2024: 5.4.2 Fix edit products bug in classic mode

15/12/2023: 5.4.1 Fix edit products bug

07/12/2023: 5.4.0 Compatible with Woocommerce HPOS.

04/04/2022: 5.3.0 Bulk actions in orders page.

04/04/2022: 5.2.0 Enable https connection for test environment.

30/11/2022: 5.1.0 Check para habilitar etiquetas para todos los transportistas.

16/11/2022: 5.0.1 Etiqueta individual desde masivos, corrección crear nuevo pedido, index.php de seguridad.

16/11/2022: 5.0.0 Modificar sistema de descarga de etiquetas, mostrar solo pedidos del último mes.

19/08/2022: 4.6.1 Permitir decimales en peso de pedido al guardar estado.

21/04/2022: 4.6 Permitir peso 0 en creación de pedido.

28/10/2021: 4.5 Corrección pesos de productos con decimales.

20/09/2021: 4.4 PHP 8 Compatible.

12/05/2021: 4.3.5 Corrección rutas para instalaciones de Wordpress no estandar. Corrección ids de pedido en pantalla de masivos.

26/01/2021: 4.3.0 Compatilizar con módulo WooCommerce Sequential Order Numbers + fix bugs

01/12/2020: 4.2.0 Permitir cambiar el peso total de la expedición manualmente desde la ficha del pedido

25/09/2020: 4.1.14 Fix tracking

01/09/2020: 4.1.13 Corrección guardar rangos a partir de Wordpress 5.5

25/08/2020: 4.1.12 Corrección guardar tasas a partir de Wordpress 5.5

30/07/2020: 4.1.11 Filtrar por id en pantalla de envíos MRW

07/07/2020: 4.1.10 Corrección filtros ordenación pantalla envíos MRW, nombre empresa "-"

26/06/2020: 4.1.9 Corrección seguimiento

30/10/2019: 4.1.8 Opción de incluir observaciones en etiquetas

19/09/2019: 4.1.7 Compatibilidad con cupones de descuento de WooCommerce

1/07/2019:  4.1.6 Sustituir funciones deprecadas

30/04/2019: 4.1.5 Envío gratuito si se aplica cupón de envío gratuito

26/03/2019: 4.1.4 Cambio de estado correcto en generación e impresión masiva

18/02/2019: 4.1.3 Gestión entrada de provincias de portugal, evitar incorporar si ya existen

08/01/2019: 4.1.2 Gestion de estados para pedidos internacionales a EEUU

25/06/2018: 4.1.0 Bug de diferenciación de Países para calcular la tarifa de envío corregido.

04/06/2018: 4.0.1 Corrección bug para permitir guardar correctamente las tasas de internacional.

27/04/2018: 4.0.0 Incorporación servicios internacionales, se añaden servicios (marítimo Baleares, marítimo Canarias, marítimo Interinsular).

06/02/2018: 3.1.0 Cambiar estado de pedido personalizado al generar la etiqueta.

11/01/2017: 3.0.0 Incorporación provincias de Portugal. Corrección bug para activar y desactivar provincias.

21/09/2017: 2.9.0 Compatibilidad con certificados SSL.

01/09/2017: 2.8.0 Nueva vista para pedidos de MRW.

18/07/2017: 2.7.0 Se añaden acciones masivas para generar e imprimir pedidos de MRW.

11/05/2017: 2.6.1 Se corrige el cálculo de tarifa si no existe rango para esas condiciones.


18/04/2017: 2.6.0 	Se añaden tramos horarios.
					Etiquetas marketplaces opcionales.
					Traducciones completas para ES, PT, EN, CA. 
					Se incluyen los servicios Marítimo baleares, Marítimo canarias y marítimo interinsular.

12/04/2017: 2.5 Corrección para calcular el peso de productos variables correctamente

06/04/2017: 2.4 Módulo compatible con todas las versiones de WooCommerce hasta la versión 3.

30/01/2017: 2.3 Se soluciona el problema para guardar gran cantidad de tasas utilizando un JSON.

18/01/2017: 2.2 Se añade compatibilidad con el módulo de cupones de WooCommerce para realizar envíos gratuitos.

16/01/2017:	2.1 Corregimos excepción no controlada al añadir metaboxes si la variable no está definida.

22/11/2016: 2.0 Número de rangos ampliado a 25

02/11/2016: 1.9   Mejoras para admitir cualquier medida de peso. Se incluye el teléfono obligatorio en los envíos terceras plazas.

04/10/2016: 1.8.4 Se añade el segundo campo de dirección. Se concatenan los dos campos de dirección para generar la etiqueta.

03/10/2016: 1.8.3 Corrección campo contacto y eliminación ALaAtencion de. Eliminación de rango de horas por defecto.

22/09/2016: 1.8.2 Corrección desglose de bultos para servicios en los que es obligatorio. Si no existe teléfono de envío enviar cadena vacía.

23/08/2016: 1.8.1 Se adapta el módulo para productos variables con diferente peso siempre y cuando la variable se llame "peso"

19/07/2016: Se compatibiliza el módulo con la versión de WooCommerce 2.6.2