DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `authUser` (IN `user` VARCHAR(50))  READS SQL DATA
SELECT usrPsw from userauth where userauth.usrId = user LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createBill` (IN `user` VARCHAR(50), IN `bDate` DATE, IN `ordNum` VARCHAR(25), IN `outlet` INT, IN `cstmr` INT, IN `vhcl` INT, IN `unq1` VARCHAR(25), IN `trnsprtr` VARCHAR(20), IN `ewayn` VARCHAR(12), IN `amt` DECIMAL(65,2), IN `cgst` DECIMAL(65,2), IN `sgst` DECIMAL(65,2), IN `igst` DECIMAL(65,2), IN `billgross` DECIMAL(65,2), IN `fqt` DECIMAL(65,2), IN `frt` DECIMAL(65,2), IN `famt` DECIMAL(65,2), IN `ftax` DECIMAL(65,2), IN `fcg` DECIMAL(65,2), IN `fsg` DECIMAL(65,2), IN `fig` DECIMAL(65,2), IN `fgros` DECIMAL(65,2), IN `rndof` DECIMAL(65,2), IN `grandt` DECIMAL(65,2))  MODIFIES SQL DATA
BEGIN
DECLARE paramId INT;
DECLARE bcreator varchar(50);
DECLARE outAdrs INT;
DECLARE cstmrAdrs INT;
DECLARE outC varchar(11);
DECLARE cstmrC varchar(11);
DECLARE vhclC varchar(11);
DECLARE billN INT;

SELECT name INTO bcreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);
SELECT adrsId, contactP INTO outAdrs, outC FROM outletinfo WHERE outletinfo.id = outlet;
SELECT adrsId, contactP INTO cstmrAdrs, cstmrC FROM customerinfo WHERE customerinfo.id = cstmr;
SELECT contactV INTO vhclC FROM vehicleinfo WHERE vehicleinfo.id = vhcl;
SELECT (billStartNum + currBillCount) INTO billN FROM outletinfo WHERE outletinfo.id = outlet;
SET paramId = getAdminId(user);

START TRANSACTION;
INSERT INTO billtransact (refId, billNum, billDate, prchsOrdNum, outId, contOut, adrsOut, cstmrId, contCstmr, adrsCstmr, vhclId, contVhcl, unqFld1, transporter, eway, pdtnet, pdtcgst, pdtsgst, pdtigst, pdtgross, fQty, fRate, fNet, fTaxRt, fcgst, fsgst, figst, fgross, rndOff, grand, creator) VALUES (paramId, billN, bDate, ordNum, outlet, outC, outAdrs, cstmr, cstmrC, cstmrAdrs, vhcl, vhclC, unq1, trnsprtr, ewayn, amt, cgst, sgst, igst, billgross, fqt, frt, famt, ftax, fcg, fsg, fig, fgros, rndof, grandt, bcreator );

UPDATE billtransact SET root = LAST_INSERT_ID(), isActive = 1 WHERE
billtransact.id = LAST_INSERT_ID();

UPDATE outletinfo SET currBillCount = currBillCount + 1, grossBillCount = grossBillCount + 1 WHERE outletinfo.id = outlet;

COMMIT;

SELECT "true" AS status, LAST_INSERT_ID() AS id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `freezeBill` (IN `dataId` INT)  MODIFIES SQL DATA
BEGIN

UPDATE billtransact SET billtransact.editPerm = 0 WHERE billtransact.id = dataId;

SELECT "true" AS status;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllAddress` (IN `dataId` INT, IN `type` INT)  READS SQL DATA
SELECT id, linked, address, state, city, pin, country FROM adrsdir WHERE adrsdir.refId = dataId AND adrsdir.type = type$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBillDetails` (IN `dataId` INT)  READS SQL DATA
SELECT id, root, isActive, billdate, billNum, outId, cstmrId, vhclId, unqFld1, prchsOrdNum, adrsShipp, transporter, eway, fRate, fQty, fNet, fTaxRt, fcgst, fsgst, figst, fgross, pdtnet, pdtcgst, pdtsgst, pdtigst, pdtgross, rndOff, grand, editPerm FROM billtransact WHERE billtransact.id = dataId AND billtransact.isActive = 1 LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBillInfo` (IN `user` VARCHAR(50), IN `fDate` DATE, IN `eDate` DATE)  READS SQL DATA
BEGIN
DECLARE paramId INT;
DECLARE oid INT;
DECLARE typ VARCHAR(15);
SELECT usrType INTO typ FROM userauth WHERE userauth.usrId = user;
SELECT outletId INTO oid FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user);
SET paramId = getAdminId(user);

CASE typ 
WHEN "control" THEN SELECT id, billdate, billNum, (SELECT name FROM outletinfo WHERE outletinfo.id = billtransact.outId) AS otId, (SELECT fname FROM customerinfo WHERE customerinfo.id = billtransact.cstmrId) AS ctmrId, (SELECT vNo FROM vehicleinfo WHERE vehicleinfo.id = billtransact.vhclId) AS vclId, biltyId, unqFld1, pdtnet, pdtgross, editPerm, creator FROM billtransact WHERE billtransact.refId = paramId AND billtransact.isActive = 1 AND billtransact.billdate BETWEEN fDate AND eDate ORDER BY id DESC;
WHEN "admin" THEN SELECT id, billdate, billNum, (SELECT name FROM outletinfo WHERE outletinfo.id = billtransact.outId) AS otId, (SELECT fname FROM customerinfo WHERE customerinfo.id = billtransact.cstmrId) AS ctmrId, (SELECT vNo FROM vehicleinfo WHERE vehicleinfo.id = billtransact.vhclId) AS vclId, biltyId, unqFld1, pdtnet, pdtgross, editPerm, creator FROM billtransact WHERE billtransact.refId = paramId AND billtransact.isActive = 1 AND billtransact.billdate BETWEEN fDate AND eDate ORDER BY id DESC;
WHEN "user" THEN SELECT id, billdate, billNum, (SELECT name FROM outletinfo WHERE outletinfo.id = billtransact.outId) AS otId, (SELECT fname FROM customerinfo WHERE customerinfo.id = billtransact.cstmrId) AS ctmrId, (SELECT vNo FROM vehicleinfo WHERE vehicleinfo.id = billtransact.vhclId) AS vclId, biltyId, unqFld1, pdtnet, pdtgross, editPerm, creator FROM billtransact WHERE billtransact.refId = paramId AND billtransact.outId = oid AND billtransact.isActive = 1 AND billtransact.billdate BETWEEN fDate AND eDate ORDER BY id DESC;
END CASE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBillItems` (IN `dataId` INT)  READS SQL DATA
SELECT pdtId, rate, taxslab, qty, unit, net, cgst, sgst, igst, gross FROM billitems WHERE billitems.billId = dataId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBrandDetails` (IN `dataId` INT)  READS SQL DATA
SELECT isActive, bName, bContact, bPlace, bIdentity FROM brandinfo WHERE brandinfo.Id = dataId LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBrandInfo` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, bName, bPlace, bContact, bIdentity, creator, isActive FROM brandinfo WHERE brandinfo.refId = paramId;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBrandList` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, bName FROM brandinfo WHERE brandinfo.refId = paramId AND brandinfo.isActive = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCstmrList` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, fname FROM customerinfo WHERE customerinfo.refId = paramId AND customerinfo.isActive = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCustomerDetails` (IN `dataId` INT)  READS SQL DATA
SELECT id, type, isActive, fName, gstn, contactP, contactS, adrsId FROM customerinfo WHERE customerinfo.Id = dataId LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCustomerInfo` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, fName, gstn, contactP, (SELECT city FROM adrsdir WHERE adrsdir.id = customerinfo.adrsId) AS district, (SELECT state FROM adrsdir WHERE adrsdir.id = customerinfo.adrsId) AS state, creator, isActive FROM customerinfo WHERE customerinfo.refId = paramId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getOutletDetails` (IN `dataId` INT)  READS SQL DATA
SELECT id, isActive, name, gstin, billStartNum, contactP, contactS, dscrb, adrsId, bankName, ifsc, accNum, brnchName FROM outletinfo WHERE outletinfo.Id = dataId LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getOutletInfo` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, isActive, name, contactP, billStartNum, (SELECT address FROM adrsdir WHERE adrsdir.id = outletinfo.adrsId) AS address, (SELECT city FROM adrsdir WHERE adrsdir.id = outletinfo.adrsId) AS city, (SELECT state FROM adrsdir WHERE adrsdir.id = outletinfo.adrsId) AS state, grossBillCount FROM outletinfo WHERE outletinfo.refId = paramId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getOutletList` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, name FROM outletinfo WHERE outletinfo.refId = paramId AND outletinfo.isActive = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getPdtList` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, name, rate, cgst FROM productinfo WHERE productinfo.refId = paramId AND productinfo.isActive = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getProductDetails` (IN `dataId` INT)  READS SQL DATA
SELECT name, hsn, brandId, rate, cgst, isActive FROM productinfo WHERE productinfo.Id = dataId LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getProductInfo` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, name, hsn, (SELECT bName FROM brandinfo WHERE brandinfo.id = productinfo.brandId) AS brand, rate, cgst, isActive, creator FROM productinfo WHERE productinfo.refId = paramId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShippingAddress` (IN `dataId` INT)  READS SQL DATA
SELECT address, state, city, pin, country FROM adrsdir WHERE adrsdir.Id = dataId AND adrsdir.type = 3$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getSiteSettings` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT billEditRule, printMode, printCopy FROM userparam WHERE userparam.unqId = paramId LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserDetails` (IN `dataId` INT)  READS SQL DATA
SELECT userauth.usrId, userauth.isActive, userinfo.name, userinfo.contactP, userinfo.outletId, userperm.billV, userperm.billC, userperm.billE, userperm.biltyV, userperm.biltyC, userperm.biltyE, userperm.pdtV, userperm.pdtC, userperm.pdtE, userperm.cstmrV, userperm.cstmrC, userperm.cstmrE, userperm.vhclV, userperm.vhclC, userperm.vhclE, userperm.brandV, userperm.brandC, userperm.brandE FROM userauth, userinfo, userperm WHERE userauth.unqId = dataId AND userinfo.unqId = dataId AND userperm.unqId = dataId LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserInfo` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT unqId, (SELECT name FROM userinfo WHERE userinfo.unqId = userperm.unqId) AS name, (SELECT isActive FROM userauth WHERE userauth.unqId = userperm.unqId) AS isActive, billV, billC, billE, biltyV, biltyC, biltyE, pdtV, pdtC, pdtE, cstmrV, cstmrC, cstmrE, vhclV, vhclC, vhclE, brandV, brandC, brandE, Muser, Moutlet, Mbiltyfirm FROM userperm WHERE userperm.refId = paramId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUsrAuth` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE today DATE;
DECLARE subEndDate DATE;
SET today = CURRENT_DATE();
SELECT subEndDt INTO subEndDate FROM userauth WHERE userauth.usrId = user LIMIT 1;

IF today > subEndDate THEN
	UPDATE userauth SET isActive = 0 WHERE userauth.usrId = user;
END IF;

SELECT usrId, usrType, isActive, subStrtDt, subEndDt FROM userauth WHERE userauth.usrId = user LIMIT 1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUsrInfo` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
DECLARE usrCount INT;
DECLARE outCount INT;
SET paramId = getAdminId(user);
SELECT COUNT(refId) INTO usrCount FROM userinfo WHERE userinfo.refId = paramId;
SELECT COUNT(refId) INTO outCount FROM outletinfo WHERE outletinfo.refId = paramId;

SELECT userinfo.name, userinfo.mail, userinfo.contactP, userinfo.contactS,usrCount, outCount, userinfo.outletId, userparam.biltyCust, userparam.unqFld1, userparam.vType, userparam.bType, userparam.bIdentifier, userparam.outType, userparam.numOutlet, userparam.numUser, userparam.billEditRule FROM userinfo, userparam WHERE userinfo.unqId = ( SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1) AND userparam.unqId = paramId LIMIT 1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUsrPerm` (IN `user` VARCHAR(50))  READS SQL DATA
SELECT billV, billC, billE, biltyV, biltyC, biltyE, pdtV, pdtC, pdtE, cstmrV, cstmrC, cstmrE, vhclV, vhclC, vhclE, brandV, brandC, brandE, Vanltcs, Muser, Moutlet, Mbiltyfirm FROM userperm WHERE userperm.unqId = ( SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1) LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getVehicleDetails` (IN `dataId` INT)  READS SQL DATA
SELECT vNo, vRep, isActive, contactV FROM vehicleinfo WHERE vehicleinfo.id = dataId LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getVehicleInfo` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, vNo, vRep, isActive, contactV, creator FROM vehicleinfo WHERE vehicleinfo.refId = paramId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getVhclList` (IN `user` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE paramId INT;
SET paramId = getAdminId(user);

SELECT id, vNo FROM vehicleinfo WHERE vehicleinfo.refId = paramId AND vehicleinfo.isActive = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `printAddress` (IN `dataId` INT)  READS SQL DATA
SELECT address, state, city, pin, country FROM adrsdir WHERE adrsdir.Id = dataId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `printBillDetails` (IN `dataId` INT)  READS SQL DATA
SELECT billdate, billNum, outId, contOut, adrsOut, cstmrId, contCstmr, adrsCstmr, vhclId, contVhcl, unqFld1, prchsOrdNum, adrsShipp, transporter, eway, fRate, fQty, fNet, fTaxRt, fcgst, fsgst, figst, fgross, pdtnet, pdtcgst, pdtsgst, pdtigst, pdtgross, rndOff, grand FROM billtransact WHERE billtransact.id = dataId AND billtransact.isActive = 1 LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `printBillItems` (IN `dataId` INT)  SELECT (SELECT name FROM productinfo WHERE productinfo.id = billitems.pdtId) AS name, (SELECT hsn FROM productinfo WHERE productinfo.id = billitems.pdtId) AS hsn, (SELECT bIdentity FROM brandinfo WHERE brandinfo.id = (SELECT brandId FROM productinfo WHERE productinfo.id = billitems.pdtId)) AS brand, rate, taxslab, qty, unit, net, cgst, sgst, igst, gross FROM billitems WHERE billitems.billId = dataId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `printOtherDetails` (IN `oId` INT, IN `cId` INT, IN `vId` INT)  READS SQL DATA
SELECT outletinfo.name, outletinfo.gstin, outletinfo.dscrb, outletinfo.bankName, outletinfo.ifsc, outletinfo.accNum, outletinfo.brnchName, customerinfo.fName, customerinfo.gstn, vehicleinfo.vNo, vehicleinfo.vRep, vehicleinfo.contactV FROM outletinfo, customerinfo, vehicleinfo WHERE outletinfo.id = oId AND customerinfo.id = cId AND vehicleinfo.id = vId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setAddress` (IN `adrsi` VARCHAR(255), IN `statei` VARCHAR(50), IN `cityi` VARCHAR(50), IN `pini` VARCHAR(6), IN `countryi` VARCHAR(50))  MODIFIES SQL DATA
BEGIN

INSERT INTO adrsdir (address, state, city, pin, country) VALUES (adrsi, statei, cityi, pini, countryi);

SELECT "true" AS status, LAST_INSERT_ID() AS id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setAdrsRefnType` (IN `dataId` INT, IN `ref` INT, IN `typ` INT)  MODIFIES SQL DATA
UPDATE adrsdir SET refId = ref, type = typ WHERE adrsdir.id = dataId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setBillItems` (IN `bill` INT, IN `pdt` INT, IN `unt` INT, IN `qt` DECIMAL(65,2), IN `rt` DECIMAL(65,2), IN `amt` DECIMAL(65,2), IN `tax` DECIMAL(65,2), IN `cg` DECIMAL(65,2), IN `sg` DECIMAL(65,2), IN `ig` DECIMAL(65,2), IN `billgross` DECIMAL(65,2))  MODIFIES SQL DATA
BEGIN

INSERT INTO billitems (billId, isActive, pdtId, rate, taxslab, qty, unit, net, cgst, sgst, igst, gross) VALUES (bill, 1, pdt, rt, tax, qt, unt, amt, cg, sg, ig, billgross);

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setBrandInfo` (IN `user` VARCHAR(50), IN `bstatus` BOOLEAN, IN `bname` VARCHAR(50), IN `bidentity` VARCHAR(50), IN `pcontact` VARCHAR(11), IN `location` VARCHAR(50))  MODIFIES SQL DATA
BEGIN
DECLARE creator varchar(50);
DECLARE owner INT;
SELECT name INTO creator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);
SET owner = getAdminId(user);

INSERT INTO brandinfo ( bName, isActive, bContact, bPlace, bIdentity, creator, refId) VALUES (bname, bstatus, pcontact, location, bidentity, creator, owner);

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setCustomerInfo` (IN `user` VARCHAR(50), IN `ctype` TINYINT, IN `cname` VARCHAR(50), IN `cgstn` VARCHAR(50), IN `pcont` VARCHAR(11), IN `scont` VARCHAR(11), IN `adrs` INT, IN `cstatus` BOOLEAN)  MODIFIES SQL DATA
BEGIN
DECLARE ccreator varchar(50);
DECLARE cowner INT;
SELECT name INTO ccreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);
SET cowner = getAdminId(user);

INSERT INTO customerinfo (type, fName, gstn, contactP, contactS, adrsId, creator, isActive, refId) VALUES (ctype, cname, cgstn, pcont, scont, adrs, ccreator, cstatus, cowner);

CALL setAdrsRefnType(adrs, LAST_INSERT_ID(), 2);

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setOutletInfo` (IN `user` VARCHAR(50), IN `ostatus` BOOLEAN, IN `billStart` INT, IN `oname` VARCHAR(50), IN `ogstin` VARCHAR(50), IN `contP` VARCHAR(11), IN `contS` VARCHAR(11), IN `odesc` VARCHAR(255), IN `adrs` INT, IN `bank` VARCHAR(50), IN `oifsc` VARCHAR(11), IN `oaccnum` VARCHAR(18), IN `branch` VARCHAR(100))  MODIFIES SQL DATA
BEGIN
DECLARE owner INT;
SET owner = getAdminId(user);

INSERT INTO outletinfo (isActive, name, gstin, billStartNum, contactP, contactS, dscrb, adrsId, bankName, ifsc, accNum, brnchName, refId) VALUES (ostatus, oname, ogstin, billStart, contP, contS, odesc,  adrs, bank, oifsc, oaccnum, branch, owner);

CALL setAdrsRefnType(adrs, LAST_INSERT_ID(), 1);

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setProductInfo` (IN `user` VARCHAR(50), IN `pstatus` BOOLEAN, IN `brand` INT, IN `pname` VARCHAR(50), IN `phsn` VARCHAR(10), IN `prate` INT, IN `tax` VARCHAR(4))  MODIFIES SQL DATA
BEGIN
DECLARE pcreator varchar(50);
DECLARE owner INT;
SELECT name INTO pcreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);
SET owner = getAdminId(user);

INSERT INTO productinfo (name, hsn, brandId, rate, cgst, isActive, creator, refId)
VALUES (pname, phsn, brand, prate, tax, pstatus, pcreator, owner);

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setShippAddress` (IN `bill` INT, IN `adrsi` VARCHAR(255), IN `statei` VARCHAR(50), IN `cityi` VARCHAR(50), IN `pini` VARCHAR(6), IN `countryi` VARCHAR(50))  MODIFIES SQL DATA
BEGIN
START TRANSACTION;
INSERT INTO adrsdir (refId, type, linked, address, state, city, pin, country) VALUES (bill, 3, 1, adrsi, statei, cityi, pini, countryi);

UPDATE billtransact SET adrsShipp = LAST_INSERT_ID() WHERE billtransact.id = bill;

COMMIT;

SELECT "true" AS status;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setUserInfo` (IN `user` VARCHAR(50), IN `logId` VARCHAR(50), IN `ustatus` BOOLEAN, IN `uname` VARCHAR(50), IN `psw` VARCHAR(255), IN `contP` VARCHAR(11), IN `outlet` INT, IN `vbill` BOOLEAN, IN `cbill` BOOLEAN, IN `ebill` BOOLEAN, IN `vbilty` BOOLEAN, IN `cbilty` BOOLEAN, IN `ebilty` BOOLEAN, IN `vpdt` BOOLEAN, IN `cpdt` BOOLEAN, IN `epdt` BOOLEAN, IN `vcstmr` BOOLEAN, IN `ccstmr` BOOLEAN, IN `ecstmr` BOOLEAN, IN `vvhcl` BOOLEAN, IN `cvhcl` BOOLEAN, IN `evhcl` BOOLEAN, IN `vbrand` BOOLEAN, IN `cbrand` BOOLEAN, IN `ebrand` BOOLEAN)  MODIFIES SQL DATA
BEGIN
DECLARE owner INT;
DECLARE endDate datetime;
DECLARE id INT;
SET owner = getAdminId(user);

SELECT subEndDt INTO endDate FROM userauth WHERE userauth.unqId = owner;

START TRANSACTION;
INSERT INTO userauth (usrId, usrPsw, usrType, isActive, subEndDt) VALUES(logId, psw, "user", ustatus, endDate);

INSERT INTO userinfo (unqId, refId, name, outletId, contactP) VALUES (LAST_INSERT_ID(), owner, uname, outlet, contP);

INSERT INTO userperm (unqId, refId, billV, billC, billE, biltyV, biltyC, biltyE, pdtV, pdtC, pdtE, cstmrV, cstmrC, cstmrE, vhclV, vhclC, vhclE, brandV, brandC, brandE) VALUES (LAST_INSERT_ID(), owner, vbill, cbill, ebill, vbilty, cbilty, ebilty, vpdt, cpdt, epdt, vcstmr, ccstmr, ecstmr, vvhcl, cvhcl, evhcl, vbrand, cbrand, ebrand);
COMMIT;

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setVehicleInfo` (IN `user` VARCHAR(50), IN `vname` VARCHAR(50), IN `vrepname` VARCHAR(50), IN `contact` VARCHAR(11), IN `vstatus` BOOLEAN)  MODIFIES SQL DATA
BEGIN
DECLARE vcreator varchar(50);
DECLARE vowner INT;
SELECT name INTO vcreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);
SET vowner = getAdminId(user);

INSERT INTO vehicleinfo (vNo, vRep, contactV, isActive, creator, refId)
VALUES (vname, vrepname, contact, vstatus, vcreator, vowner);

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateAddress` (IN `dataId` INT, IN `adrsi` VARCHAR(255), IN `statei` VARCHAR(50), IN `cityi` VARCHAR(50), IN `pini` VARCHAR(6), IN `countryi` VARCHAR(50))  MODIFIES SQL DATA
BEGIN
DECLARE lnk INT;
SELECT linked INTO lnk FROM adrsdir WHERE adrsdir.id = dataId;

CASE lnk
WHEN 0 THEN UPDATE adrsdir SET address = adrsi, state = statei, city = cityi, pin = pini, country = countryi WHERE adrsdir.id = dataId;
SELECT "true" AS status;
WHEN 1 THEN SELECT "true" AS status;
END CASE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBill` (IN `user` VARCHAR(50), IN `oldbill` INT, IN `bDate` DATE, IN `ordNum` VARCHAR(25), IN `outlet` INT, IN `cstmr` INT, IN `vhcl` INT, IN `unq1` VARCHAR(25), IN `trnsprtr` VARCHAR(20), IN `ewayn` VARCHAR(12), IN `amt` DECIMAL(65,2), IN `cgst` DECIMAL(65,2), IN `sgst` DECIMAL(65,2), IN `igst` DECIMAL(65,2), IN `billgross` DECIMAL(65,2), IN `fqt` DECIMAL(65,2), IN `frt` DECIMAL(65,2), IN `famt` DECIMAL(65,2), IN `ftax` DECIMAL(65,2), IN `fcg` DECIMAL(65,2), IN `fsg` DECIMAL(65,2), IN `fig` DECIMAL(65,2), IN `fgros` DECIMAL(65,2), IN `rndof` DECIMAL(65,2), IN `grandt` DECIMAL(65,2))  MODIFIES SQL DATA
BEGIN
DECLARE paramId INT;
DECLARE bcreator varchar(50);
DECLARE outAdrs INT;
DECLARE cstmrAdrs INT;
DECLARE outC varchar(11);
DECLARE cstmrC varchar(11);
DECLARE vhclC varchar(11);
DECLARE billN INT;
DECLARE rootId INT;

SELECT name INTO bcreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);
SELECT adrsId, contactP INTO outAdrs, outC FROM outletinfo WHERE outletinfo.id = outlet;
SELECT adrsId, contactP INTO cstmrAdrs, cstmrC FROM customerinfo WHERE customerinfo.id = cstmr;
SELECT contactV INTO vhclC FROM vehicleinfo WHERE vehicleinfo.id = vhcl;
SELECT billNum, root INTO billN, rootId FROM billtransact WHERE billtransact.id = oldbill;
SET paramId = getAdminId(user);

START TRANSACTION;
INSERT INTO billtransact (refId, root, isActive, billNum, billDate, prchsOrdNum, outId, contOut, adrsOut, cstmrId, contCstmr, adrsCstmr, vhclId, contVhcl, unqFld1, transporter, eway, pdtnet, pdtcgst, pdtsgst, pdtigst, pdtgross, fQty, fRate, fNet, fTaxRt, fcgst, fsgst, figst, fgross, rndOff, grand, creator) VALUES (paramId, rootId, 1, billN, bDate, ordNum, outlet, outC, outAdrs, cstmr, cstmrC, cstmrAdrs, vhcl, vhclC, unq1, trnsprtr, ewayn, amt, cgst, sgst, igst, billgross, fqt, frt, famt, ftax, fcg, fsg, fig, fgros, rndof, grandt, bcreator );

UPDATE billtransact SET isActive = 0 WHERE
billtransact.id = oldbill;

COMMIT;

SELECT "true" AS status, LAST_INSERT_ID() AS id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBillItems` (IN `bill` INT, IN `oldbill` INT, IN `pdt` INT, IN `unt` INT, IN `qt` DECIMAL(65,2), IN `rt` DECIMAL(65,2), IN `amt` DECIMAL(65,2), IN `tax` DECIMAL(65,2), IN `cg` DECIMAL(65,2), IN `sg` DECIMAL(65,2), IN `ig` DECIMAL(65,2), IN `billgross` DECIMAL(65,2))  MODIFIES SQL DATA
BEGIN
START TRANSACTION;
INSERT INTO billitems (billId, isActive, pdtId, rate, taxslab, qty, unit, net, cgst, sgst, igst, gross) VALUES (bill, 1, pdt, rt, tax, qt, unt, amt, cg, sg, ig, billgross);

UPDATE billitems SET isActive = 0 WHERE billitems.billId = oldbill;

COMMIT;

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBrandInfo` (IN `user` VARCHAR(50), IN `dataId` INT, IN `bstatus` BOOLEAN, IN `bname` VARCHAR(50), IN `bidentity` VARCHAR(50), IN `pcontact` VARCHAR(11), IN `location` VARCHAR(50))  MODIFIES SQL DATA
BEGIN
DECLARE creator varchar(50);
DECLARE islnkd tinyint(1);
SELECT linked INTO islnkd FROM brandinfo WHERE brandinfo.id = dataId;
SELECT name INTO creator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);

CASE islnkd
WHEN 1 THEN UPDATE brandinfo 
SET isActive = bstatus, bName = bname, bPlace = location, bContact = pcontact, creator = creator WHERE brandinfo.id = dataId;
WHEN 0 THEN UPDATE brandinfo 
SET isActive = bstatus, bName = bname, bPlace = location, bContact = pcontact, bIdentity = bidentity, creator = creator WHERE brandinfo.id = dataId;
END CASE;

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCustomerInfo` (IN `user` VARCHAR(50), IN `dataId` INT, IN `ctype` TINYINT, IN `cname` VARCHAR(50), IN `cgstn` VARCHAR(50), IN `pcont` VARCHAR(11), IN `scont` VARCHAR(11), IN `adrs` INT, IN `cstatus` BOOLEAN)  MODIFIES SQL DATA
BEGIN
DECLARE ccreator varchar(50);
DECLARE islnkd tinyint(1);
SELECT linked INTO islnkd FROM customerinfo WHERE customerinfo.id = dataId;
SELECT name INTO ccreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);

CASE islnkd
WHEN 1 THEN UPDATE customerinfo 
SET type = ctype, contactP = pcont, contactS = scont, adrsId = adrs, isActive = cstatus, creator = ccreator WHERE customerinfo.id = dataId;
WHEN 0 THEN UPDATE customerinfo 
SET type = ctype, fName = cname, gstn = cgstn, contactP = pcont, contactS = scont, adrsId = adrs, isActive = cstatus, creator = ccreator WHERE customerinfo.id = dataId;
END CASE;

CALL setAdrsRefnType(adrs, dataId, 2);

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateOutletInfo` (IN `dataId` INT, IN `ostatus` BOOLEAN, IN `renew` BOOLEAN, IN `billStart` INT, IN `oname` VARCHAR(50), IN `ogstin` VARCHAR(50), IN `contP` VARCHAR(11), IN `contS` VARCHAR(11), IN `odesc` VARCHAR(255), IN `adrs` INT, IN `bank` VARCHAR(50), IN `oifsc` VARCHAR(11), IN `oaccnum` VARCHAR(18), IN `branch` VARCHAR(100))  MODIFIES SQL DATA
BEGIN
DECLARE islnkd tinyint(1);
SELECT linked INTO islnkd FROM outletinfo WHERE outletinfo.id = dataId;
START TRANSACTION;

CASE islnkd
WHEN 1 THEN UPDATE outletinfo 
SET isActive = ostatus, contactP = contP, contactS = contS, dscrb = odesc,adrsId = adrs, bankName = bank, ifsc = oifsc, accNum = oaccnum, brnchName = branch WHERE outletinfo.id = dataId;
WHEN 0 THEN UPDATE outletinfo 
SET isActive = ostatus, name = oname, gstin = ogstin, contactP = contP, contactS = contS, dscrb = odesc,adrsId = adrs, bankName = bank, ifsc = oifsc, accNum = oaccnum, brnchName = branch WHERE outletinfo.id = dataId;
END CASE;

CASE renew
	WHEN 1 THEN UPDATE outletinfo SET billStartNum = billStart, currBillCount = 0 WHERE outletinfo.id = dataId;
SELECT "true";
    WHEN 0 THEN SELECT "true";
END CASE;

CALL setAdrsRefnType(adrs, dataId, 1);

COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updatePassword` (IN `user` VARCHAR(50), IN `psw` VARCHAR(255))  MODIFIES SQL DATA
BEGIN
UPDATE userauth SET usrPsw = psw WHERE userauth.usrId = user;

select "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateProductInfo` (IN `user` INT(50), IN `dataId` INT, IN `pstatus` BOOLEAN, IN `brand` INT, IN `pname` VARCHAR(50), IN `phsn` VARCHAR(10), IN `prate` INT, IN `tax` VARCHAR(4))  MODIFIES SQL DATA
BEGIN
DECLARE pcreator varchar(50);
DECLARE islnkd tinyint(1);
SELECT linked INTO islnkd FROM productinfo WHERE productinfo.id = dataId;
SELECT name INTO pcreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);

CASE islnkd
WHEN 1 THEN UPDATE productinfo SET rate = prate, cgst = tax, isActive = pstatus, creator = pcreator WHERE productinfo.id = dataId;
WHEN 0 THEN UPDATE productinfo 
SET name = pname, hsn = phsn, brandId = brand, rate = prate, cgst = tax, isActive = pstatus, creator = pcreator WHERE productinfo.id = dataId;
END CASE;

SELECT "true";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateSiteSettings` (IN `user` VARCHAR(50), IN `rule` VARCHAR(1), IN `style` INT, IN `copy` INT)  MODIFIES SQL DATA
BEGIN
DECLARE paramId INT;
SELECT unqId INTO paramId FROM userauth WHERE userauth.usrId = user LIMIT 1;

UPDATE userparam SET billEditRule = rule, printMode = style, printCopy = copy WHERE userparam.unqId = paramId;

SELECT "true";

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUserInfo` (IN `dataId` INT, IN `ustatus` BOOLEAN, IN `uname` VARCHAR(50), IN `renew` BOOLEAN, IN `psw` VARCHAR(255), IN `contP` VARCHAR(11), IN `outlet` INT, IN `vbill` BOOLEAN, IN `cbill` BOOLEAN, IN `ebill` BOOLEAN, IN `vbilty` BOOLEAN, IN `cbilty` BOOLEAN, IN `ebilty` BOOLEAN, IN `vpdt` BOOLEAN, IN `cpdt` BOOLEAN, IN `epdt` BOOLEAN, IN `vcstmr` BOOLEAN, IN `ccstmr` BOOLEAN, IN `ecstmr` BOOLEAN, IN `vvhcl` BOOLEAN, IN `cvhcl` BOOLEAN, IN `evhcl` BOOLEAN, IN `vbrand` BOOLEAN, IN `cbrand` BOOLEAN, IN `ebrand` BOOLEAN)  MODIFIES SQL DATA
BEGIN
START TRANSACTION;
UPDATE userauth SET isActive = ustatus WHERE userauth.unqId = dataId;

UPDATE userinfo SET name = uname, outletId = outlet, contactP = contP WHERE userinfo.unqId = dataId;

UPDATE userperm  SET billV = vbill, billC = cbill, billE = ebill, biltyV = vbilty, biltyC = cbilty, biltyE = ebilty, pdtV = vpdt, pdtC = cpdt, pdtE = epdt, cstmrV = vcstmr, cstmrC = ccstmr, cstmrE = ecstmr, vhclV = vvhcl, vhclC = cvhcl, vhclE = evhcl, brandV = vbrand, brandC = cbrand, brandE = ebrand WHERE userperm.unqId = dataId;

CASE renew
	WHEN 1 THEN UPDATE userauth SET usrPsw = psw WHERE userauth.unqId = dataId;
SELECT "true";
	WHEN 0 THEN SELECT "true";
END CASE;

COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateVehicleInfo` (IN `user` VARCHAR(50), IN `dataId` INT, IN `vname` VARCHAR(50), IN `vrepname` VARCHAR(50), IN `contact` VARCHAR(11), IN `vstatus` BOOLEAN)  MODIFIES SQL DATA
BEGIN
DECLARE vcreator varchar(50);
DECLARE islnkd tinyint(1);
SELECT linked INTO islnkd FROM vehicleinfo WHERE vehicleinfo.id = dataId;
SELECT name INTO vcreator FROM userinfo WHERE userinfo.unqId = (SELECT unqId FROM userauth WHERE userauth.usrId = user LIMIT 1);

CASE islnkd
WHEN 1 THEN UPDATE vehicleinfo 
SET isActive = vstatus, vRep = vrepname, contactV = contact, creator = vcreator WHERE vehicleinfo.id = dataId;
WHEN 0 THEN UPDATE vehicleinfo 
SET isActive = vstatus, vNo = vname, vRep = vrepname, contactV = contact, creator = vcreator WHERE vehicleinfo.id = dataId;
END CASE;

SELECT "true";
END$$


DELIMITER ;
