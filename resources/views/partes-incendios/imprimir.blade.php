<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parte de Incendio #{{ str_pad($partesIncendio->numero_parte, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @media print {
            @page {
                size: letter;
                margin: 1cm;
            }
            
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 12px;
                line-height: 1.4;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .print-header {
                display: block !important;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
        }
        
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
        }
        
        .print-header {
            display: none;
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }
        
        .header h4 {
            margin: 5px 0;
            font-size: 13px;
        }
        
        .header h5 {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 11px;
            background-color: #f0f0f0;
            padding: 3px 8px;
            margin-bottom: 5px;
            border-left: 3px solid #dc3545;
        }
        
        .field {
            margin-bottom: 5px;
        }
        
        .field-label {
            font-weight: bold;
            display: inline-block;
            min-width: 150px;
        }
        
        .field-value {
            display: inline-block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        
        table th, table td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            font-size: 11px;
            text-align: left;
        }
        
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .signatures {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 15px;
        }
        
        .signature-box {
            display: inline-block;
            width: 30%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        
        .border {
            border: 1px solid #ddd;
            padding: 8px;
            margin-bottom: 8px;
        }
        
        .page-number {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 10px;
            color: #666;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0,0,0,0.1);
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <!-- Botones para imprimir (solo en pantalla) -->
    <div class="no-print" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
        <button onclick="window.print()" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🖨️ Imprimir
        </button>
        <button onclick="window.close()" style="padding: 10px 15px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            ❌ Cerrar
        </button>
    </div>
    
    <!-- Encabezado de impresión -->
    <div class="print-header">
        <strong>Parte de Incendio #{{ str_pad($partesIncendio->numero_parte, 4, '0', STR_PAD_LEFT) }}</strong> | 
        Fecha: {{ $partesIncendio->fecha->format('d/m/Y') }} |
        Impreso: {{ date('d/m/Y H:i') }}
    </div>
    
    <!-- Marca de agua -->
    <div class="watermark">COPIA DE CONTROL</div>
    
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h3>CUERPO GENERAL DE BOMBEROS VOLUNTARIOS DEL PERÚ</h3>
            <h4>XI COMANDANCIA DEPARTAMENTAL DE LORETO</h4>
            <h4>Compañía de Bomberos "BELEN" Nº 41</h4>
            <h5>PARTES DE SERVICIO Nº {{ str_pad($partesIncendio->numero_parte, 4, '0', STR_PAD_LEFT) }}</h5>
        </div>
        
        <!-- Clasificación del servicio -->
        <div class="section">
            <div class="section-title">CLASIFICACIÓN DEL SERVICIO</div>
            <div class="field">
                <span class="field-label">Clasificación:</span>
                <span class="field-value">{{ $partesIncendio->clasificacion_servicio_texto }}</span>
            </div>
            <div class="field">
                <span class="field-label">Fecha:</span>
                <span class="field-value">{{ $partesIncendio->fecha->format('d/m/Y') }}</span>
            </div>
            <div class="field">
                <span class="field-label">Hora de Salida:</span>
                <span class="field-value">{{ $partesIncendio->hora_salida }}</span>
            </div>
            <div class="field">
                <span class="field-label">Hora de Retorno:</span>
                <span class="field-value">{{ $partesIncendio->hora_retorno }}</span>
            </div>
            <div class="field">
                <span class="field-label">Persona que recepcionó el aviso:</span>
                <span class="field-value">{{ $partesIncendio->persona_recepciono_aviso }}</span>
            </div>
        </div>
        
        <!-- Información de la máquina -->
        <div class="section">
            <div class="section-title">INFORMACIÓN DE LA MÁQUINA</div>
            <div class="field">
                <span class="field-label">Máquina:</span>
                <span class="field-value">{{ $partesIncendio->maquina }}</span>
            </div>
            <div class="field">
                <span class="field-label">Al mando de la máquina:</span>
                <span class="field-value">{{ $partesIncendio->mando_maquina }}</span>
            </div>
            <div class="field">
                <span class="field-label">Piloto de la máquina:</span>
                <span class="field-value">{{ $partesIncendio->piloto_maquina }}</span>
            </div>
        </div>
        
        <!-- Dirección y causa -->
        <div class="section">
            <div class="section-title">UBICACIÓN Y CAUSA DE LA EMERGENCIA</div>
            <div class="field mb-2">
                <div class="field-label">Dirección de la emergencia:</div>
                <div class="field-value">{{ $partesIncendio->direccion_emergencia }}</div>
            </div>
            <div class="field">
                <div class="field-label">Posible causa de la emergencia:</div>
                <div class="field-value">{{ $partesIncendio->causa_emergencia }}</div>
            </div>
        </div>
        
        <!-- Detalles del inmueble/vehículo -->
        @if($partesIncendio->clase_inmueble || $partesIncendio->tipo_construccion || $partesIncendio->vehiculo)
        <div class="section">
            <div class="section-title">DETALLES DEL INMUEBLE / VEHÍCULO</div>
            @if($partesIncendio->clase_inmueble || $partesIncendio->tipo_construccion)
            <div class="field">
                <span class="field-label">Clase de inmueble:</span>
                <span class="field-value">{{ $partesIncendio->clase_inmueble }}</span>
            </div>
            <div class="field">
                <span class="field-label">Tipo de construcción:</span>
                <span class="field-value">{{ $partesIncendio->tipo_construccion }}</span>
            </div>
            @endif
            
            @if($partesIncendio->vehiculo)
            <div class="field">
                <span class="field-label">Vehículo:</span>
                <span class="field-value">{{ $partesIncendio->vehiculo }}</span>
            </div>
            <div class="field">
                <span class="field-label">Modelo:</span>
                <span class="field-value">{{ $partesIncendio->modelo_vehiculo }}</span>
            </div>
            <div class="field">
                <span class="field-label">Placa:</span>
                <span class="field-value">{{ $partesIncendio->placa_vehiculo }}</span>
            </div>
            <div class="field">
                <span class="field-label">Color:</span>
                <span class="field-value">{{ $partesIncendio->color_vehiculo }}</span>
            </div>
            <div class="field">
                <span class="field-label">Marca:</span>
                <span class="field-value">{{ $partesIncendio->marca_vehiculo }}</span>
            </div>
            @endif
            
            @if($partesIncendio->danos_vehiculo)
            <div class="field">
                <div class="field-label">Daños observados:</div>
                <div class="field-value">{{ $partesIncendio->danos_vehiculo }}</div>
            </div>
            @endif
        </div>
        @endif
        
        <!-- Personas lesionadas -->
        @if($partesIncendio->personas_lesionadas)
        <div class="section">
            <div class="section-title">PERSONAS LESIONADAS</div>
            <table>
                <thead>
                    <tr>
                        <th>Nombres y Apellidos</th>
                        <th>Documento de Identidad</th>
                        <th>Clase de Lesiones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partesIncendio->personas_lesionadas as $persona)
                    <tr>
                        <td>{{ $persona['nombre'] ?? '' }}</td>
                        <td>{{ $persona['documento'] ?? '' }}</td>
                        <td>{{ $persona['lesiones'] ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Trabajo efectuado -->
        <div class="section">
            <div class="section-title">TRABAJO EFECTUADO</div>
            <div class="field">
                <div class="field-label">Descripción:</div>
                <div class="field-value">{{ $partesIncendio->trabajo_efectuado }}</div>
            </div>
            <div class="field">
                <div class="field-label">Material utilizado:</div>
                <div class="field-value">{{ $partesIncendio->material_utilizado }}</div>
            </div>
        </div>
        
        <!-- Unidades de apoyo -->
        @if($partesIncendio->unidades_apoyo_cgbvp || $partesIncendio->unidades_policiales)
        <div class="section">
            <div class="section-title">UNIDADES DE APOYO</div>
            @if($partesIncendio->unidades_apoyo_cgbvp)
            <div class="field">
                <div class="field-label">Unidades de apoyo CGBVP:</div>
                <div class="field-value">{{ $partesIncendio->unidades_apoyo_cgbvp }}</div>
            </div>
            @endif
            
            @if($partesIncendio->mando_operaciones_cgbvp)
            <div class="field">
                <span class="field-label">Al mando de las operaciones CGBVP:</span>
                <span class="field-value">{{ $partesIncendio->mando_operaciones_cgbvp }}</span>
            </div>
            @endif
            
            @if($partesIncendio->unidades_policiales)
            <div class="field">
                <div class="field-label">Unidades policiales:</div>
                <div class="field-value">{{ $partesIncendio->unidades_policiales }}</div>
            </div>
            @endif
        </div>
        @endif
        
        <!-- Personal asistente -->
        @if($partesIncendio->personal_asistente)
        <div class="section">
            <div class="section-title">PERSONAL ASISTENTE A LA EMERGENCIA</div>
            <div class="row">
                @foreach($partesIncendio->personal_asistente as $asistente)
                <div style="width: 50%; float: left; margin-bottom: 5px;">{{ $asistente }}</div>
                @endforeach
                <div style="clear: both;"></div>
            </div>
        </div>
        @endif
        
        <!-- Información adicional -->
        <div class="section">
            <div class="section-title">INFORMACIÓN ADICIONAL</div>
            
            @if($partesIncendio->personal_bombero_accidentado)
            <div class="field">
                <div class="field-label">Personal bombero accidentado:</div>
                <div class="field-value">{{ $partesIncendio->personal_bombero_accidentado }}</div>
            </div>
            @endif
            
            @if($partesIncendio->personal_asistente_cuartel)
            <div class="field">
                <div class="field-label">Personal asistente en el cuartel:</div>
                <div class="field-value">{{ $partesIncendio->personal_asistente_cuartel }}</div>
            </div>
            @endif
            
            @if($partesIncendio->damnificados_incendios)
            <div class="field">
                <div class="field-label">Damnificados por incendios:</div>
                <div class="field-value">{{ $partesIncendio->damnificados_incendios }}</div>
            </div>
            @endif
            
            @if($partesIncendio->observaciones)
            <div class="field">
                <div class="field-label">Observaciones:</div>
                <div class="field-value">{{ $partesIncendio->observaciones }}</div>
            </div>
            @endif
        </div>
        
        <!-- Firmas -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Nombre y Firma del Bombero al<br>Mando de la operación</div>
                <div style="margin-top: 5px;"><strong>{{ $partesIncendio->firma_bombero_mando }}</strong></div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Nombre y Firma del Bombero<br>que confecciona el parte</div>
                <div style="margin-top: 5px;"><strong>{{ $partesIncendio->firma_bombero_confecciona }}</strong></div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Vº Bº<br>Segundo Jefe de Compañía</div>
                <div style="margin-top: 5px;"><strong>{{ $partesIncendio->firma_segundo_jefe }}</strong></div>
            </div>
            <div style="clear: both;"></div>
        </div>
        
        <!-- Información del sistema -->
        <div class="text-center mt-3" style="font-size: 9px; color: #666;">
            <hr style="border-top: 1px dashed #ccc; margin: 10px 0;">
            Partes de Incendio - Sistema de Gestión de Bomberos<br>
            Impreso el: {{ date('d/m/Y H:i:s') }} | Usuario: {{ $partesIncendio->user->name }}
        </div>
    </div>
    
    <!-- Número de página -->
    <div class="page-number no-print">
        Página 1 de 1
    </div>

    <script>
    // Configuración para impresión
    window.onload = function() {
        // Configurar título de la ventana de impresión
        document.title = "Parte Incendio #{{ str_pad($partesIncendio->numero_parte, 4, '0', STR_PAD_LEFT) }}";
    };
    
    // Manejar antes de imprimir
    window.onbeforeprint = function() {
        // Puedes agregar lógica adicional aquí si es necesario
    };
    
    // Manejar después de imprimir
    window.onafterprint = function() {
        // Puedes agregar lógica adicional aquí si es necesario
    };
    </script>
</body>
</html>