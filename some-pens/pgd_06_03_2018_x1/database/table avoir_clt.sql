USE [pgd_ma_10]
GO

/****** Object:  Table [dbo].[avoir_client]    Script Date: 10/01/2018 10:20:08 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[avoir_client](
	[IdRetour] [int] IDENTITY(1,1) NOT NULL,
	[DateR] [varchar](50) NULL,
	[HeureR] [varchar](50) NULL,
	[IdFacture] [int] NOT NULL,
	[IdArticle] [int] NOT NULL,
	[EncienQte] [int] NOT NULL,
	[QteRetour] [int] NOT NULL,
	[IdDepot] [int] NOT NULL,
	[EtatAvoir] [int] NULL,
	[IdSupperviseur] [int] NULL,
 CONSTRAINT [PK_avoir_client] PRIMARY KEY CLUSTERED 
(
	[IdRetour] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[avoir_client] ADD  CONSTRAINT [DF_avoir_client_EtatAvoir]  DEFAULT ((0)) FOR [EtatAvoir]
GO


