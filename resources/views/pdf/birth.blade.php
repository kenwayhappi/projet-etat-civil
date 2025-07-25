<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acte de Naissance</title>
    <style>
        /* Styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: serif; /* Utilisation d'une police par défaut (ex. Times New Roman) */
            background: #ffffff; /* Fond blanc pour compatibilité PDF */
            padding: 10px; /* Réduit pour éviter les marges excessives */
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
            font-size: 60px; /* Réduit pour éviter le débordement */
            color: rgba(30, 60, 114, 0.05);
            font-weight: bold;
            z-index: 1;
            pointer-events: none;
        }

        .header {
            background: #667eea;
            color: white;
            padding: 15px; /* Réduit */
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .header h1 {
            font-size: 2rem; /* Réduit */
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 0.9rem; /* Réduit */
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .header .republic {
            font-size: 0.7rem; /* Réduit */
            opacity: 0.8;
            border-top: 1px solid rgba(255, 255, 255, 0.5);
            padding-top: 5px;
            margin-top: 5px;
        }

        .content {
            padding: 20px; /* Réduit */
            position: relative;
            z-index: 2;
            background: #ffffff;
        }

        .act-number {
            text-align: center;
            margin-bottom: 15px; /* Réduit */
            padding: 5px; /* Réduit */
            background: #f5576c;
            color: white;
            border-radius: 5px;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px; /* Réduit */
            margin-bottom: 15px; /* Réduit */
        }

        .info-item {
            background: #f8f9fa;
            padding: 10px; /* Réduit */
            border-radius: 5px;
            border-left: 3px solid #667eea;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.8rem; /* Réduit */
            margin-bottom: 3px; /* Réduit */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #212529;
            font-size: 0.9rem; /* Réduit */
            line-height: 1.2; /* Réduit */
            font-weight: 500;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .validation-section {
            background: #84fab0;
            padding: 15px; /* Réduit */
            border-radius: 5px;
            margin-top: 15px; /* Réduit */
            text-align: center;
            color: #2c5530;
        }

        .validation-title {
            font-size: 1rem; /* Réduit */
            font-weight: 700;
            margin-bottom: 5px; /* Réduit */
        }

        .validation-details {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 10px; /* Réduit */
        }

        .validation-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 10px; /* Réduit */
            border-radius: 5px;
            flex: 1;
            min-width: 150px; /* Réduit */
        }

        .footer {
            background: #f8f9fa;
            padding: 10px; /* Réduit */
            text-align: center;
            border-top: 1px solid #e9ecef; /* Réduit */
        }

        .footer-text {
            color: #6c757d;
            font-size: 0.7rem; /* Réduit */
            font-style: italic;
        }

        .seal {
            position: absolute;
            bottom: 10px; /* Réduit */
            right: 20px; /* Réduit */
            width: 50px; /* Réduit */
            height: 50px; /* Réduit */
            border: 2px solid #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            font-size: 0.5rem; /* Réduit */
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
                <h1>ACTE DE NAISSANCE</h1>
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
                    <div class="info-label">Nom complet de l'enfant</div>
                    <div class="info-value">{{ $act->details['child_name'] ?? 'Non renseigné' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Date de naissance</div>
                    <div class="info-value">{{ isset($act->details['birth_date']) ? \Carbon\Carbon::parse($act->details['birth_date'])->format('d/m/Y') : 'Non renseignée' }}</div>
                </div>

                <div class="info-item full-width">
                    <div class="info-label">Lieu de naissance</div>
                    <div class="info-value">{{ $act->details['birth_place'] ?? 'Non renseigné' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nom du père</div>
                    <div class="info-value">{{ $act->details['father_name'] ?? 'Non déclaré' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nom de la mère</div>
                    <div class="info-value">{{ $act->details['mother_name'] ?? 'Non déclarée' }}</div>
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
