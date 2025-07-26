<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acte de Divorce</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: serif;
            background: #ffffff;
            padding: 10px;
        }

        .document {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #1e3c72;
            border-radius: 5px;
            overflow: hidden;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(30, 60, 114, 0.05);
            font-weight: bold;
            z-index: 1;
            pointer-events: none;
        }

        .header {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .header .republic {
            font-size: 0.7rem;
            opacity: 0.8;
            border-top: 1px solid rgba(255, 255, 255, 0.5);
            padding-top: 5px;
            margin-top: 5px;
        }

        .content {
            padding: 20px;
            position: relative;
            z-index: 2;
            background: #ffffff;
        }

        .act-number {
            text-align: center;
            margin-bottom: 15px;
            padding: 5px;
            background: #f5576c;
            color: white;
            border-radius: 5px;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 3px solid #667eea;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.8rem;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #212529;
            font-size: 0.9rem;
            line-height: 1.2;
            font-weight: 500;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .validation-section {
            background: #84fab0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            text-align: center;
            color: #2c5530;
        }

        .validation-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .validation-details {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 10px;
        }

        .validation-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 10px;
            border-radius: 5px;
            flex: 1;
            min-width: 150px;
        }

        .footer {
            background: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer-text {
            color: #6c757d;
            font-size: 0.7rem;
            font-style: italic;
        }

        .seal {
            position: absolute;
            bottom: 10px;
            right: 20px;
            width: 50px;
            height: 50px;
            border: 2px solid #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            font-size: 0.5rem;
            text-align: center;
            color: #667eea;
            font-weight: bold;
            z-index: 3;
        }

        @media print {
            body {
                padding: 0;
            }

            .document {
                border: none;
                border-radius: 0;
            }

            .seal {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .document {
                margin: 5px;
            }

            .header {
                padding: 10px;
            }

            .content {
                padding: 10px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .seal {
                position: static;
                margin: 10px auto 0;
            }
        }
    </style>
</head>
<body>
    <div class="document">
        <div class="watermark">OFFICIEL</div>

        <div class="header">
            <div class="header-content">
                <h1>ACTE DE DIVORCE</h1>
                <div class="subtitle">État Civil Officiel</div>
                <div class="republic">
                    République du Cameroun<br>
                    Ministère de l'Administration Territoriale et de la Décentralisation<br>
                    Centre : {{ $center->name ?? 'Non spécifié' }}<br>
                    Date d'émission : {{ now()->format('d/m/Y') }}<br>
                    N° d'enregistrement : {{ $act->id }}
                </div>
            </div>
        </div>

        <div class="content">
            <div class="act-number">
                <strong>N° {{ $act->id }}</strong> - Document Officiel
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom de l'époux</div>
                    <div class="info-value">{{ $act->details['spouse1_name'] ?? 'Non renseigné' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nom de l'épouse</div>
                    <div class="info-value">{{ $act->details['spouse2_name'] ?? 'Non renseignée' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Date de divorce</div>
                    <div class="info-value">{{ isset($act->details['divorce_date']) ? \Carbon\Carbon::parse($act->details['divorce_date'])->format('d/m/Y') : 'Non renseignée' }}</div>
                </div>
            </div>

            <div class="validation-section">
                <div class="validation-title">Document Validé et Certifié</div>
                <div class="validation-details">
                    <div class="validation-item">
                        <div class="info-label">Validé par</div>
                        <div class="info-value">{{ $supervisor->name ?? 'Autorité compétente' }}</div>
                    </div>
                    <div class="validation-item">
                        <div class="info-label">Date de validation</div>
                        <div class="info-value">{{ $act->updated_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-text">
                Document officiel généré le {{ now()->format('d/m/Y à H:i') }}<br>
                Ce document fait foi selon la législation en vigueur
            </div>
        </div>

        <div class="seal">
            SCEAU<br>OFFICIEL
        </div>
    </div>
</body>
</html>

