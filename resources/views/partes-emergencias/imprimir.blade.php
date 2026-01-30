<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parte de Emergencia #{{ str_pad($partesEmergencia->numero_parte, 4, '0', STR_PAD_LEFT) }}</title>
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
            width: 45%;
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
        <strong>Parte de Emergencia #{{ str_pad($partesEmergencia->numero_parte, 4, '0', STR_PAD_LEFT) }}</strong> | 
        Fecha: {{ $partesEmergencia->fecha->format('d/m/Y') }} |
        Impreso: {{ date('d/m/Y H:i') }}
    </div>
    
    <!-- Marca de agua -->
    <div class="watermark">COPIA DE CONTROL</div>
    
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h3>CUERPO GENERAL DE BOMBEROS VOLUNTARIOS DEL PERÚ</h3>
            <h4>XI COMANDANCIA DEPARTAMENTAL LORETO</h4>
            <h4>CIA. DE BOMBEROS BELEN N° 41</h4>
            <h5>PARTE DE EMERGENCIA MÉDICA N° {{ str_pad($partesEmergencia->numero_parte, 4, '0', STR_PAD_LEFT) }}</h5>
        </div>
        
        <!-- Fecha -->
        <div class="section">
            <div class="field">
                <span class="field-label">Fecha:</span>
                <span class="field-value">{{ $partesEmergencia->fecha->format('d/m/Y') }}</span>
            </div>
        </div>
        
        <!-- Información básica -->
        <div class="section">
            <div class="section-title">INFORMACIÓN BÁSICA</div>
            <div class="row" style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <div class="field">
                        <span class="field-label">Hora de Salida:</span>
                        <span class="field-value">{{ $partesEmergencia->hora_salida }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Hora de Ingreso:</span>
                        <span class="field-value">{{ $partesEmergencia->hora_ingreso }}</span>
                    </div>
                </div>
                <div style="width: 48%;">
                    <div class="field">
                        <span class="field-label">Piloto de Máquina:</span>
                        <span class="field-value">{{ $partesEmergencia->piloto_maquina }}</span>
                    </div>
                </div>
            </div>
            <div class="field">
                <span class="field-label">N° Velocímetro Salida:</span>
                <span class="field-value">{{ $partesEmergencia->velocimetro_salida ?? '---' }}</span>
                <span style="margin-left: 20px;" class="field-label">N° Velocímetro Retorno:</span>
                <span class="field-value">{{ $partesEmergencia->velocimetro_retorno ?? '---' }}</span>
            </div>
        </div>
        
        <!-- Clasificación del servicio -->
        <div class="section">
            <div class="section-title">CLASIFICACIÓN DEL SERVICIO</div>
            @php
                $clasificacionesSeleccionadas = $partesEmergencia->clasificacion_servicio;
                if (is_string($clasificacionesSeleccionadas)) {
                    $clasificacionesSeleccionadas = json_decode($clasificacionesSeleccionadas, true) ?? [];
                }
            @endphp
            
            <div style="display: flex; flex-wrap: wrap;">
                @foreach($clasificaciones as $key => $label)
                <div style="width: 33%; margin-bottom: 5px;">
                    @if(in_array($key, $clasificacionesSeleccionadas))
                    ☑ {{ $label }}
                    @else
                    □ {{ $label }}
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Información del lugar -->
        <div class="section">
            <div class="section-title">INFORMACIÓN DEL LUGAR Y TRASLADO</div>
            @if($partesEmergencia->tipo_lugar)
            <div class="field">
                <span class="field-label">Tipo de lugar:</span>
                <span class="field-value">{{ $partesEmergencia->tipo_lugar }}</span>
            </div>
            @endif
            
            <div class="field mb-2">
                <div class="field-label">Dirección:</div>
                <div class="field-value">{{ $partesEmergencia->direccion_incidente }}</div>
            </div>
            
            @if($partesEmergencia->traslado_a)
            <div class="field">
                <span class="field-label">Se trasladó a:</span>
                <span class="field-value">{{ $partesEmergencia->traslado_a }}</span>
            </div>
            @endif
            
            @if($partesEmergencia->persona_responsable)
            <div class="field">
                <span class="field-label">Persona Responsable:</span>
                <span class="field-value">{{ $partesEmergencia->persona_responsable }}</span>
            </div>
            @endif
        </div>
        
        <!-- Persona afectada -->
        @if($partesEmergencia->nombre_afectado)
        <div class="section">
            <div class="section-title">PERSONA(S) AFECTADA(S)</div>
            <div class="field">
                <span class="field-label">Nombre:</span>
                <span class="field-value">{{ $partesEmergencia->nombre_afectado }}</span>
            </div>
            <div class="field">
                <span class="field-label">DNI N°:</span>
                <span class="field-value">{{ $partesEmergencia->dni_afectado ?? '---' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Edad:</span>
                <span class="field-value">{{ $partesEmergencia->edad_afectado ?? '---' }}</span>
                <span style="margin-left: 20px;" class="field-label">Sexo:</span>
                <span class="field-value">
                    @if($partesEmergencia->sexo_afectado)
                        {{ $partesEmergencia->sexo_afectado == 'M' ? 'Masculino' : 'Femenino' }}
                    @else
                        ---
                    @endif
                </span>
            </div>
        </div>
        @endif
        
        <!-- Signos vitales -->
        <div class="section">
            <div class="section-title">SIGNOS VITALES</div>
            <table>
                <tr>
                    <th>P.A. (mmhg)</th>
                    <th>Pulso (x min)</th>
                    <th>Respiración (x min)</th>
                    <th>Temperatura (°C)</th>
                </tr>
                <tr>
                    <td>{{ $partesEmergencia->presion_arterial ?? '---' }}</td>
                    <td>{{ $partesEmergencia->pulso ?? '---' }}</td>
                    <td>{{ $partesEmergencia->respiracion ?? '---' }}</td>
                    <td>{{ $partesEmergencia->temperatura ?? '---' }}</td>
                </tr>
            </table>
            
            <div style="margin-top: 10px;">
                <strong>Neurológico:</strong>
                <div style="margin-left: 20px;">
                    Consciente: {{ $partesEmergencia->neurologico_consciente ?? '---' }}<br>
                    Inconsciente: {{ $partesEmergencia->neurologico_inconsciente ?? '---' }}
                </div>
                <div style="margin-top: 5px;">
                    Pupilas: {{ $partesEmergencia->pupilas ?? '---' }} | 
                    SpO2: {{ $partesEmergencia->spo2 ?? '---' }}
                </div>
            </div>
        </div>
        
        <!-- Información médica -->
        <div class="section">
            <div class="section-title">INFORMACIÓN MÉDICA</div>
            
            <div class="field mb-2">
                <div class="field-label">Lesiones observadas:</div>
                <div class="field-value">{{ $partesEmergencia->lesiones_observadas }}</div>
            </div>
            
            <div class="field mb-2">
                <div class="field-label">Diagnóstico presuntivo:</div>
                <div class="field-value">{{ $partesEmergencia->diagnostico_presuntivo }}</div>
            </div>
            
            <div class="field mb-2">
                <div class="field-label">Tratamiento y trabajo efectuado:</div>
                <div class="field-value">{{ $partesEmergencia->tratamiento_efectuado }}</div>
            </div>
            
            @if($partesEmergencia->material_utilizado)
            <div class="field mb-2">
                <div class="field-label">Material utilizado:</div>
                <div class="field-value">{{ $partesEmergencia->material_utilizado }}</div>
            </div>
            @endif
            
            @if($partesEmergencia->observaciones)
            <div class="field">
                <div class="field-label">Observaciones:</div>
                <div class="field-value">{{ $partesEmergencia->observaciones }}</div>
            </div>
            @endif
        </div>
        
        <!-- Unidades -->
        <div class="section">
            <div class="section-title">UNIDADES</div>
            @if($partesEmergencia->unidades_cgbvp)
            <div class="field mb-2">
                <div class="field-label">Unidades del CGBVP:</div>
                <div class="field-value">{{ $partesEmergencia->unidades_cgbvp }}</div>
            </div>
            @endif
            
            @if($partesEmergencia->unidades_policiales)
            <div class="field">
                <div class="field-label">Unidades Policiales:</div>
                <div class="field-value">{{ $partesEmergencia->unidades_policiales }}</div>
            </div>
            @endif
        </div>
        
        <!-- Concurrentes -->
        <div class="section">
            <div class="section-title">CONCURRENTES</div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <strong>Concurrentes:</strong>
                    @if($partesEmergencia->concurrentes)
                        @php
                            $concurrentes = explode("\n", $partesEmergencia->concurrentes);
                        @endphp
                        @foreach($concurrentes as $concurrente)
                            @if(trim($concurrente))
                                <div>{{ $concurrente }}</div>
                            @endif
                        @endforeach
                    @else
                        <div>---</div>
                    @endif
                </div>
                <div style="width: 48%;">
                    <strong>No Concurrentes:</strong>
                    @if($partesEmergencia->no_concurrentes)
                        @php
                            $noConcurrentes = explode("\n", $partesEmergencia->no_concurrentes);
                        @endphp
                        @foreach($noConcurrentes as $noConcurrente)
                            @if(trim($noConcurrente))
                                <div>{{ $noConcurrente }}</div>
                            @endif
                        @endforeach
                    @else
                        <div>---</div>
                    @endif
                </div>
            </div>
            
            <div style="margin-top: 10px; text-align: center;">
                <strong>Total concurrentes: {{ $partesEmergencia->total_concurrentes }}</strong>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Total no concurrentes: {{ $partesEmergencia->total_no_concurrentes }}</strong>
            </div>
        </div>
        
        <!-- Firmas -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>NOMBRE Y FIRMA DEL BOMBERO AL<br>MANDO DE LA OPERACIÓN</div>
                <div style="margin-top: 5px;"><strong>{{ $partesEmergencia->firma_bombero_mando ?? '______________________' }}</strong></div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>NOMBRE Y FIRMA DEL BOMBERO<br>QUE CONFECCIONÓ EL PARTE</div>
                <div style="margin-top: 5px;"><strong>{{ $partesEmergencia->firma_bombero_confecciona }}</strong></div>
            </div>
            <div style="clear: both;"></div>
        </div>
        
        <!-- Información del sistema -->
        <div class="text-center mt-3" style="font-size: 9px; color: #666;">
            <hr style="border-top: 1px dashed #ccc; margin: 10px 0;">
            Parte de Emergencia Médica - Sistema de Gestión de Bomberos<br>
            Impreso el: {{ date('d/m/Y H:i:s') }} | Usuario: {{ $partesEmergencia->user->name }}
        </div>
    </div>
    
    <!-- Número de página -->
    <div class="page-number no-print">
        Página 1 de 1
    </div>

    <script>
    window.onload = function() {
        document.title = "Parte Emergencia #{{ str_pad($partesEmergencia->numero_parte, 4, '0', STR_PAD_LEFT) }}";
    };
    </script>
</body>
</html>